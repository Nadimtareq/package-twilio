<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<title>Email Template</title>
	<style>
		@import
		url('https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700');


		   .ReadMsgBody { width: 100%; background-color: #f5f5f5; }
		   html { width: 100%; }
		   body { background-color: #f5f5f5; -webkit-text-size-adjust: none;
		-ms-text-size-adjust: none; margin: 0; padding: 0; }
		   table { border-spacing: 0; border-collapse: collapse; }
		   table td { border-collapse: collapse; }
		   .yshortcuts a { border-bottom: none !important; }
		   img { display: block !important; }
		   a { text-decoration: none;}
		   table[class="table800"]{width: 800px;}

		   /* Media Queries */
		   @media only screen and (max-width: 640px) {
			body { width: auto !important; }
			table[class="table600"] { width: 450px !important; }
			table[class="table800"] { width: 100% !important; }
			table[class="table-container"] { width: 90% !important; }
			table[class="container2-2"] { width: 47% !important; text-align:
		left !important; }
			table[class="full-width"] { width: 100% !important; text-align:
		center !important; }
			table[class="full-width-left"] { width: 90% !important; text-align:
		left !important; }
			table[class="full-width-right"] { width: 90%  !important;
		text-align: right !important; }
			img[class="img-full"] { width: 100% !important; height: auto; }
		   }

		   @media only screen and (max-width: 479px) {
			body { width: auto !important; }
			table[class="table600"] { width: 290px !important; }
			table[class="table800"] { width: 100% !important; }
			table[class="table-container"] { width: 82% !important; }
			table[class="container2-2"] { width: 100% !important; text-align:
		left !important; }
			table[class="full-width"] { width: 100% !important; text-align:
		center !important; }
			table[class="full-width-left"] { width: 82% !important; text-align:
		left !important; }
			table[class="full-width-right"] { width: 82%  !important;
		text-align: right !important; }
			img[class="img-full"] { width: 100% !important; }
		   }


		.text span.start{
			font: 18px 'LatoBold' !important;
			display: block;
			text-align: center;
			margin-top: -195px !important;
			letter-spacing: 0.6px;
		}

		.text{
			position: absolute !important;
			color: white !important;
			top: 0;
			left: 40;
			display: block;
			width: 280px;
			margin: 0 auto;
		}
		.text strong.bax{
			font: 46px 'LatoBold';
			display: block;
			text-align: center;
			margin-top: 10px;
			letter-spacing: 0.6px;
			-moz-transform: rotate(-7deg) !important; /* Ãâ€ÃÂ»Ã‘Â Firefox */
			-ms-transform: rotate(-7deg) !important; /* Ãâ€ÃÂ»Ã‘Â IE */
			-webkit-transform: rotate(-7deg) !important; /* Ãâ€ÃÂ»Ã‘Â Safari, Chrome, iOS */
			-o-transform: rotate(-7deg) !important; /* Ãâ€ÃÂ»Ã‘Â Opera */
			transform: rotate(-7deg) !important;
		}
		.text strong.bax{
			font: 25px 'LatoBold';
		}
		.text strong.number{
			font: 70px 'LatoBold' !important;
			text-align: center;
			display: inline-block;
			margin-top: -18px;
			vertical-align: top;
			letter-spacing: 0.6px;
			-moz-transform: rotate(-7deg) !important;
			-ms-transform: rotate(-7deg) !important;
			-webkit-transform: rotate(-7deg) !important;
			-o-transform: rotate(-7deg) !important;
			transform: rotate(-7deg) !important;
		}
		.text p{
			font: 12px 'LatoBlack';
			margin-top: 5px;
			text-align: center;
			margin-bottom: 0;
		}
		.text span.crop{
			margin: 0;
			display: block;
			text-align: right;
			margin-right: 79px;
		}
  </style>
</head>
<body style="margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" offset="0" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
	<!-- head starts -->
	<table data-module="module-02" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody>
   <tr>
    <td bgcolor="#283593" align="center">
     <table class="table-container" border="0" width="700" cellspacing="0" cellpadding="0">
      <tbody>
	  <tr>
       <td height="15"></td>
      </tr>
      <tr>
       <td>
        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="150" cellspacing="0" cellpadding="0" align="left">
			<tbody>
			 <tr>
			  <td width="24" align="center"><img src="{{asset('images/logo.png')}}" style="margin-top:5px;" alt="Logo"></td>
			 </tr>
			</tbody>
		</table>
        <table height="10" width="30" align="left">
         <tbody>
		 <tr>
          <td></td>
         </tr>
        </tbody>
		</table>
        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="150" cellspacing="0" cellpadding="0" align="right">
			<tbody>
			 <tr>
			  <td align="left">
				<a href="{{url('auth/set/'.$user['saldo_url'])}}" style="color: #fff; font-family: 'Poppins', sans-serif; font-size:16px; margin-top:12px;float:left;text-decoration:none;"><strong>Saldo €{{$user["saldo"]}}</strong></a>
			  </td>
			 </tr>
			</tbody>
		</table>
       </td>
      </tr>
      <tr>
       <td height="15"></td>
      </tr>
     </tbody></table>
    </td>
   </tr>
  </tbody>
  </table>
	<!-- head ends -->
  <table data-module="module-07" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td bgcolor="#fff" align="center">
     <table class="table-container" border="0" width="780" cellspacing="0" cellpadding="0">
      <tbody>

			<!-- extension part  -->
@if ($user["extension_downloaded"]==0)
	<tr style="margin-top:20px;float:left;" align="left">
		 <td>
			<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
			 <tbody><tr>
				<td>
				 <table class="full-width" border="0" width="300" cellspacing="0" cellpadding="0" align="left">
			<tbody>
			<tr>
			 <td align="left" style="position: relative;text-align: center;"><img width="340" src="{{asset('images/laptop-text.png')}}" alt='laptop' />
			 </td>
			</tr>
			 </tbody>
		 </table>
				 <table width="20" align="left">
					<tbody><tr>
					 <td></td>
					</tr>
				 </tbody></table>
				 <!-- text -->

				 <table class="full-width-left" border="0" width="385" cellspacing="0" cellpadding="0" align="left">
					<tbody>
		<tr>
					 <td height="10"></td>
					</tr>
					<tr>
					 <td height="30"></td>
					</tr>
					<tr>
					 <td>
						<table>
						 <tbody><tr>
							<td style="font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 600; color:#121619;">Activeer de spaarhulp en ontvang direct €5.-</td>
						 </tr>
						</tbody></table>
					 </td>
					</tr>
					<tr>
					 <td>
						<table>
						 <tbody><tr>
							<td style="font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 400; color: #808080;">Spaar nu automatisch bij wel 2000+ webshops. Deze betalen u tot wel 10% dinertegoed bij iedere aankoop!</td>
						 </tr>
						</tbody></table>
					 </td>
					</tr>
					<tr>
					 <td height="10"></td>
					</tr>
					<tr>
					 <td>
						<table align="left">
						 <tbody><tr>
							<td><a style="font-family: 'Poppins', sans-serif; font-size: 16px;background:#283593;padding:10px 26px ;border-radius:5px; font-weight: normal; color: #fff;" href="{{url('auth/set/'.$user['extension_download_url'])}}">Ja ik wil ook sparen!</a></td>
						 </tr>
						</tbody></table>
					 </td>
					</tr>
					<tr>
					 <td height="10"></td>
					</tr>
				 </tbody>
		 </table>
				 <table>
					<tbody><tr>
					 <td width="20"></td>
					</tr>
				 </tbody></table>
				</td>
			 </tr>
			</tbody></table>
		 </td>
		</tr>
		<tr>
			<td height="30"></td>
		</tr>
		<tr>
			<td height="1" bgcolor="#283593"></td>
		</tr>
		<tr>
			<td height="40"></td>
		</tr>
		@endif
			<!-- extension part ends -->
	  <tr>
       <td height="40" style="font-size:30px;font-family: 'Poppins', sans-serif;font-weight:normal;margin-bottom: 20px;float: left;color:#283593">De beste deals:</td>
      </tr>
      <tr>
       <td height="10"></td>
      </tr>
     </tbody></table>
    </td>
   </tr>
  </tbody>
  </table>
  <table data-module="module-08" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td bgcolor="#fbfbfb" align="center">
     <table class="table-container" border="0" width="780" cellspacing="0" cellpadding="0">
      <tbody>
			@foreach ($deals as $key => $restaurant)
			<?php $i=0; ?>
				@foreach($restaurant as $deal)
				<?php
					$image = $deal['image']!='' ? $deal['image'] : 'no-img.jpg';
				?>
				 @if($i%2==0)
					<tr>
		       <td>
		        <table class="full-width" width="385" align="left">
		         <tbody><tr>
		          <td><a href="{{ url('auth/set/'.$deal['deal_url']) }}"><img  width="100%" height="264.13px" class="img-full" src="{{asset('images/deals/'.$image) }}" alt="{{$deal['name']}}"></a></td>
		         </tr>
		         <tr>
		          <td height="20"></td>
		         </tr>
		         <tr>
		          <td align="left">
		           <table align="left">
		            <tbody><tr>
		             <td style="font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 500; color: #121619;" align="left">{{$deal['name']}}</td>
		            </tr>
		           </tbody></table>
		           <table align="right">
		            <tbody><tr>
		             <td></td>
		             <td width="5"></td>
		             <td style="font-family: 'Poppins', sans-serif; font-size:14px; font-weight:700; color:#283593;"><a href="{{ url('auth/set/'.$deal['deal_url']) }}"><strike><small style="color:gray;">€{{$deal['price_from']}}</small></strike> &nbsp; <big>€{{$deal['price']}}</big></a></td>
		            </tr>
		           </tbody></table>
		          </td>
		         </tr>
					 </tbody></table>
						<table height="20" align="left">
							<tbody><tr>
								<td></td>
							</tr>
						</tbody></table>
						@elseif($i%2!=0)
		        <table class="full-width" width="385" align="right">
		         <tbody><tr>
		          <td><a href="{{ url('auth/set/'.$deal['deal_url']) }}"><img class="img-full" width="100%" height="264.13px" src="{{asset('images/deals/'.$image) }}" alt="{{$deal['name']}}"></a></td>
		         </tr>
		         <tr>
		          <td height="20"></td>
		         </tr>
		         <tr>
		          <td align="left">
		           <table align="left">
		            <tbody><tr>
		             <td style="font-family: 'Poppins', sans-serif; font-size:14px; font-weight:500; color:#121619;" align="left">{{$deal['name']}}</td>
		            </tr>
		           </tbody></table>
		           <table align="right">
		            <tbody><tr>
		             <td></td>
		             <td width="5"></td>
		             <td style="font-family: 'Poppins', sans-serif; font-size:14px; font-weight:700; color:#283593;"><a href="{{ url('auth/set/'.$deal['deal_url']) }}"><strike><small style="color:gray;">€{{$deal['price_from']}}</small></strike> &nbsp;<big>€{{$deal['price']}} </big></a></td>
		            </tr>
		           </tbody></table>
		          </td>
		         </tr>
					 </tbody></table>
		       </td>
		      </tr>
		      <tr>
		       <td height="20"></td>
		      </tr>
					@endif
					<?php $i+=1; ?>
      	@endforeach
				@if(count($restaurant)%2!=0)
						</td>
					</tr>
					<tr>
					 <td height="20"></td>
					</tr>
				@endif
      @endforeach
      <tr>
       <td style="font-size: 50px; line-height: 50px;" height="50"></td>
      </tr>
     </tbody></table>
    </td>
   </tr>
  </tbody>
  </table>
  <table data-module="module-15" class="table800" style="width:800px;max-width:800px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
   <tbody><tr>
    <td bgcolor="#fbfbfb" align="center">
     <table class="table-container" border="0" width="780" cellspacing="0" cellpadding="0">
      <tbody><tr>
       <td height="10"></td>
      </tr>
      <tr>
       <td>
        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="280" cellspacing="0" cellpadding="0" align="left">
         <tbody><tr>
          <td><a href="#" style="color: #131619; font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 400; text-decoration: none;">© 2017 UWvoordeelpas. All rights reserved.</a></td>
         </tr>
        </tbody></table>
        <table width="30" align="left">
         <tbody><tr>
          <td></td>
         </tr>
        </tbody></table>
        <table class="full-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" width="100" cellspacing="0" cellpadding="0" align="right">
         <tbody><tr>
          <td>
           <table align="center">
            <tbody><tr>
             <td>
              <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0" align="right">
               <tbody><tr>
                <td><a href="{{url('auth/set/'.$user['unsubscribe_url'])}}" style="color: #283593; font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 400; text-decoration: none;">Unsubscribe</a></td>
               </tr>
              </tbody></table>
             </td>
            </tr>
           </tbody></table>
          </td>
         </tr>
        </tbody></table>
       </td>
      </tr>
      <tr>
       <td height="5"></td>
      </tr>
     </tbody></table>
    </td>
   </tr>
  </tbody>
  </table>
</body>
</html>
