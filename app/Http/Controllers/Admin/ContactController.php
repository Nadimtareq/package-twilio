<?php

namespace App\Http\Controllers\Admin;
use Alert;
use App;
use App\Models\ContactFormMessage;
use App\Models\MailTemplate;
use App\User;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Sentinel;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->slugController = 'contact';
        $this->section = 'Contact';
    }

    public function index(Request $request){
        $data = Contact::select(
            '*'
        );

        if ($request->has('q')) {
            $data = $data->where('title', 'LIKE', '%'.$request->input('q').'%');
        }

        if ($request->has('sort') && $request->has('order')) {
            $data = $data->orderBy($request->input('sort'), $request->input('order'));

            session(['sort' => $request->input('sort'), 'order' => $request->input('order')]);
        } else {
            $data = $data->orderBy('id', 'desc');
        }
        $data = $data->paginate($request->input('limit', 15));
        $queryString = $request->query();
        return view('admin/'.$this->slugController.'/index', [
            'data' => $data,
            'slugController' => $this->slugController,
            'paginationQueryString' => $request->query(),
            'queryString' => $queryString,
            'limit' => $request->input('limit', 15),
            'section' => $this->section,
//            'category' => Config::get('preferences.faq'),
            'currentPage' => 'Contact List'
        ]);
    }

    public function deleteAction(Request $request)
    {
        $data = Contact::whereIn('id', $request->input('id'));

        if(Sentinel::inRole('admin') == FALSE) {
            $data = $data->where('user_id', Sentinel::getUser()->id);
        }

        switch ($request->input('action')) {
            case 'remove':
                $data->delete();

                Alert::success('De gekozen selectie is succesvol verwijderd.')->persistent("Sluiten");
                return Redirect::to('admin/'.$this->slugController);
                break;
            default:
                alert()->message('Please choose an action')->persistent("Ok");
                return Redirect::to('admin/'.$this->slugController);
                break;
        }
    }

    public function readAction($id)
    {
        $data = Contact::find($id);

        $data->status = "read";
        $data->save();

        if ($data) {
            return view('admin/' . $this->slugController . '/read', [
                'data' => $data,
                'slugController' => $this->slugController,
                'section' => $this->section,
                'currentPage' => 'Contact List'
            ]);
        }
        else
            abort(404);
    }

    public function replyAction($id, Request $request)
    {
        $contact = Contact::find($id);

        $contact->status = "replied";
        $contact->save();

        if ($contact) {

            $contactFormMessage = new ContactFormMessage();
            $contactFormMessage->contact_reply_id = $contact->id;
            $contactFormMessage->message = $request->reply;
            $contactFormMessage->sender_id = Sentinel::getUser()->id;
            $contactFormMessage->recipient_id = null;
            $contactFormMessage->recipient_email = $contact->email;
            $contactFormMessage->sender_name = Sentinel::getUser()->name;
            $contactFormMessage->Recipient_name = $contact->name;
            $contactFormMessage->unread = 1;
            $contactFormMessage->toCustomer = 1;
            $contactFormMessage->save();


            #Send Email to user by admin
            $data = [
                'reply' => $request->reply,
                'contact' => $contact,
                'conversation_link' => url("contact/conversation/" . $contactFormMessage->id . "/" . $contactFormMessage->recipient_email)
            ];
            try {
                Mail::send('emails.contact-reply', $data, function ($message) use ($contact) {
                    $message->to($contact->email)
                        ->subject("Antwoord: " . $contact->subject);
                });
            } catch (\Swift_RfcComplianceException $e) {
//                return $e;
            }

            Alert::success('', 'Uw bericht is succesvol verzonden.  Wij hopen u zo snel mogelijk antwoord te kunnen geven.')->persistent('Sluiten');

            return Redirect::to('/');
        }
        else
            abort(404);
    }

    public function conversation($id, $email)
    {
        $contactForm = ContactFormMessage::where("id", $id)->where("recipient_email", $email)->first();

        if($contactForm) {
            $contact = Contact::find($contactForm->contact_reply_id);

            if ($contactForm && $contact) {
                $conversations = ContactFormMessage::where("id", $id)->where("recipient_email", $email)->get();

                return view('admin/' . $this->slugController . '/messages', [
                    'data' => $conversations,
                    'contact' => $contact,
                    'slugController' => $this->slugController,
                    'section' => $this->section,
                    'currentPage' => 'Contact Conversation',
                    'id' => $id,
                    "email" => $email
                ]);
            }
        }
        exit(404);
    }

    public function saveConversation($id, $email, Request $request)
    {

        $contactForm = ContactFormMessage::where("id", $id)->where("recipient_email", $email)->first();
        $contact = Contact::find($contactForm->contact_reply_id);

        if ($contactForm && $contact)
        {
            //We expect this user to be the admin that responded to the user since he's the first to reply
            $newRecipient = User::find($contactForm->sender_id);

            $conversations = ContactFormMessage::where("id", $id)->where("recipient_email", $email)->latest();

            $contactFormMessage = new ContactFormMessage();

            if (Sentinel::check()) {
                if (!Sentinel::getUser()->email == $contact->email) {
                    $contactFormMessage->recipient_id = null;
                    $contactFormMessage->recipient_email = $contact->email;
                    $contactFormMessage->Recipient_name = $contact->name;

                    $data = [
                        'reply' => $request->reply,
                        'contact' => $contact,
                        'conversation_link' => url("contact/conversation/" . $contactFormMessage->id . "/" . $contactFormMessage->recipient_email)
                    ];
                    try {
                        Mail::send('emails.contact-reply', $data, function ($message) use ($contact, $newRecipient) {
                            $message->to($contact->email)
                                ->subject("Antwoord: " . $contact->subject);
                        });
                    } catch (\Swift_RfcComplianceException $e) {
                    }
                }
                else {

                    $data = [
                        'reply' => $request->reply,
                        'contact' => $contact,
                        'conversation_link' => url("contact/conversation/" . $contactFormMessage->id . "/" . $contactFormMessage->recipient_email)
                    ];
                    try {
                        Mail::send('emails.contact-reply', $data, function ($message) use ($contact, $newRecipient) {
                            $message->to($newRecipient->email)
                                ->subject("Antwoord: " . $contact->subject);
                        });
                    } catch (\Swift_RfcComplianceException $e) {
                    }

                    $contactFormMessage->recipient_id = $newRecipient->id;
                    $contactFormMessage->recipient_email = $newRecipient->email;
                    $contactFormMessage->Recipient_name = $newRecipient->name;
                }

                $contactFormMessage->sender_id = Sentinel::getUser()->id;
                $contactFormMessage->sender_name = Sentinel::getUser()->name;
            }
            else{
                $contactFormMessage->sender_id = null;
                $contactFormMessage->sender_name = $contact->name;
            }

            $contactFormMessage->contact_reply_id = $contact->id;
            $contactFormMessage->message = $request->reply;

            $contactFormMessage->unread = 1;
            $contactFormMessage->toCustomer = 0;
            $contactFormMessage->save();

            Alert::success('Uw bericht is succesvol verzonden!')->persistent("Sluiten");
            return Redirect::to('admin/'.$this->slugController);
        }

        exit(404);
    }

}
