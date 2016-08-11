<?php
	$message = '
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Email details</title>    
	</head>

	<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<center>
			<table style="padding:30px 10px;background:#F4F4F4;width:100%;font-family:arial" cellpadding="0" cellspacing="0">
					
					<tbody>
						<tr>
							<td>
							
								<table style="max-width:600px;min-width:500px;margin-left:125px;" align="center" cellspacing="0">
									<tbody>
									
										<tr>
											<td style="background:#fff;border:1px solid #D8D8D8;padding:30px 30px" align="center">
											
												<table align="center" style="min-width: 450px;">
													<tbody>
													
														<tr>
															<td style="border-bottom:1px solid #D8D8D8;color:#666;text-align:center;padding-bottom:30px">
																
																<table style="margin:auto" align="center">
																	<tbody>
																		<tr>
																			<td style="color:#005f84;font-size:22px;font-weight:bold;text-align:center;font-family:arial">
																				<span style="color:#0971f1">Detalii email | Orar ACE</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														
														<tr>
												   <td style="color:#666;padding:15px; padding-bottom:0;font-size:14px;line-height:20px;font-family:arial;text-align:left">
										
														<div style="font-style:normal;padding-bottom:15px;font-family:arial;line-height:20px;text-align:left">
															
															<p><span style="font-weight:bold;font-size:16px">Primit de la :</span> '.$_POST["sendername"].'</p><br/>
															<p><span style="font-weight:bold;font-size:16px">Subiect: </span> '.$_POST["sendersubject"].'</p><br/>
															<p><span style="font-weight:bold;font-size:16px;">Mesaj: </span> </p>
															<p style="margin-bottom:0;"> '.$_POST["sendermessage"].' </p>
															
														  </div>
																
															</td>
														</tr>
														
													</tbody>
												</table>
												
											</td>
										</tr>
										
									</tbody>
								</table>
							</td>
						</tr>
						
						<tr>
							<td>
								<table style="max-width:650px" align="center">
									
									<tbody>
										<tr>
											<td style="color:#b4b4b4;font-size:11px;padding-top:10px;line-height:15px;font-family:arial">
												<span style="margin-left: 180px;font-size: 14px;color: blueviolet;"> &copy; Facultatea de Automatica, Calculatoare si Electronica, 2016 </span>
											</td>
				
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
				</tbody>
			</table>
		</center>
	</body>
	</html>';
?>