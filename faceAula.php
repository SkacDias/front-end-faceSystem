<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
	.toast-center-center { top: 50%; left: 50%; transform: translate(-50%, -50%); }

	body{
		font-family: sans-serif;
		margin: 0;
	}

	.area{
		margin: 10px auto;
		box-shadow: 0 10px 100px #ccc;
		padding: 20px;
		box-sizing: border-box;
		max-width: 500px;
	}

	.area video{
		width: 100%;
		height: auto;
		background-color: whitesmoke;
	}

	.area textarea{
		width: 100%;
		margin-top: 10px;
		height: 80px;
		box-sizing: border-box;
	}

	.area button{
		-webkit-appearance: none;
		width: 100%;
		box-sizing: border-box;
		padding: 10px;
		text-align: center;
		background-color: #068c84;
		color: white;
		text-transform: uppercase;
		border: 1px solid white;
		box-shadow: 0 1px 5px #666;
	}

	.area button:focus{
		outline: none;
		background-color: #0989b0;
	}

	.area img{
		max-width: 100%;
		height: auto;
	}

	.area .caminho-imagem{
		padding: 5px 10px;
		border-radius: 3px;
		background-color: #068c84;
		text-align: center;
	}

	.area .caminho-imagem a{
		color: white;
		text-decoration: none;
	}

	.area .caminho-imagem a:hover{
		color: yellow;
	}
</style>
<head>

	<title>Autenticação Facial</title>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google" content="notranslate" />
	
	<script src="/assets/jquery/jquery.min.js"></script>
	<script src="/assets/toast/toastr.js"></script>

	<link rel="stylesheet" type="text/css" href="/assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css"></link>
	<link rel="stylesheet" type="text/css" href="/assets/css/util.css"></link>
	<link rel="stylesheet" type="text/css" href="/assets/css/main.css"></link>
	<link rel="stylesheet" type="text/css" href="/assets/css/m2br.dialog.css"></link>
	<link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon"></link>
	<link href="/assets/toast/build/toastr.css" rel="stylesheet"></link>
	
	<script src="/assets/toast/toastr.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
	<script>
		var date = new Date();
		var time = date.toLocaleTimeString();
		var data = new Date();
		var retorno_codigo = "";
		var dados_computerID = "";
		var retorno_validacao = "";
		var retorno_validacao_codigo = "";
		var result_index = "";
		var result_nome = "";
		var result_time = "";
		var dados_grava_validacao_facial = "";
		var dados_recupera = "";
		var video = document.querySelector("#video-teste");
		var canvas = "";
		var ctx = "";
		var file = "";
		var reader = new FileReader();
		var s = "";
		var video = "";
		var saveIdUser = "";
		var authenticate = 0;
		var countId = 0;

		function myGreeting() {
			toastr.remove();
		}
		
		function displayTime() {
			takeSnapShot();
		}

		const createClock = setInterval(displayTime, 2000);
		
		function Confirma() {
			data_cadastro_Inicial = new Date();
			console.log(dataURI);
			dados_computerID = {
				"fotob64": dataURI,
			}
			$.ajax({
				type: "POST",
				url: "https://cid.apifacialid.com.br/wsfacecid/v2/identify",
				data: JSON.stringify(dados_computerID),
				headers: {"Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9jbGllbnRlIjoxMCwiaWRfY2xpZW50ZV90YWdlZCI6MjAsImVtYWlsIjoiYW50b25pby5tb3JhaXNAbWFjdWNvdGVjaC5jb20uYnIiLCJ0YWdzIjpbIlByb2R1Y2FvIl19.nro_4eOKyo0A_-sU6QIzBDjPQXHL0upQHwMb4pfb7X8"},
				success: function(result){
					retorno_validacao = JSON.stringify(result);
					posicao_code = retorno_validacao.indexOf("code");
					posicao_idPessoa = retorno_validacao.indexOf("idPessoa");
					retorno_code = retorno_validacao.substr(posicao_code+6,1);
					retorno_idPessoa = retorno_validacao.substr(posicao_idPessoa+11,11);
					//console.log(retorno_validacao);
					//console.log(retorno_code);
					//console.log(retorno_idPessoa);
					switch (retorno_code) {
						case "0":
							toastr.optionsOverride = 'positionclass = "toast-center-center"';
							toastr.options.positionClass = 'toast-center-center';
							setTimeout(toastr.remove(), 5000);
							toastr.success('<b>Erro inesperado</b>', {closeButton: true, timeOut: 50000, progressBar: true, allowHtml: true, positionClass: 'toast-top-center'});										
							break;
						case "2":
							dados_recupera = {
								"idPessoa": retorno_idPessoa,
							}
							pageurl = 'face_aula_recover.php';
							$.ajax({
								url: pageurl,
								data: dados_recupera,
								type: 'POST',
								cache: false,
								error: function(XMLHttpRequest, textStatus, errorThrown)
								{
									toastr.optionsOverride = 'positionclass = "toast-center-center"';
									toastr.options.positionClass = 'toast-center-center';
									setTimeout(toastr.remove(), 5000);
									toastr.success('<b>1 - Erro: Acessar functiono</b>' + XMLHttpRequest, {closeButton: true, timeOut: 50000, progressBar: true, allowHtml: true, positionClass: 'toast-top-center'});																			
									for(i in XMLHttpRequest)
									{
										if(i!="channel")
										{
											document.write(i +" : " + XMLHttpRequest[i] +"<br>")
										}
									}
								},
								success: function(result)
								{
									resultIndex = result.split("*");
									result_nome = resultIndex[0];
									result_time = resultIndex[1];
									dados_grava_validacao_facial = {
										"idPessoa": retorno_idPessoa,
										"returnCode": retorno_code,
									}
									pageurl = 'class_presence_record.php';
									$.ajax({
										url: pageurl,
										data: dados_grava_validacao_facial,
										type: 'POST',
										cache: false,
										error: function(XMLHttpRequest, textStatus, errorThrown)
										{
											toastr.optionsOverride = 'positionclass = "toast-center-center"';
											toastr.options.positionClass = 'toast-center-center';
											setTimeout(toastr.remove(), 5000);
											toastr.success('<b>2 - Erro: Acessar function</b>' + XMLHttpRequest, {closeButton: true, timeOut: 50000, progressBar: true, allowHtml: true, positionClass: 'toast-top-center'});										
											for(i in XMLHttpRequest)
											{
												if(i!="channel")
												{
													document.write(i +" : " + XMLHttpRequest[i] +"<br>")
												}
											}
										},
										success: function(result)
										{
											if (result == "RECORDED") {
												if (saveIdUser != retorno_idPessoa) {
													authenticate = 1;
													saveIdUser = retorno_idPessoa;
													toastr.optionsOverride = 'positionclass = "toast-center-center"';
													toastr.options.positionClass = 'toast-center-center';
													setTimeout(toastr.remove(), 5000);
													toastr.success('<b>PRESENÇA EM AULA REALIZADA COM SUCESSO</b><br></br><b>Aluno: </b>' + result_nome + '<br></br><b>Data</b> ' + data.getDate() + "/" + (parseInt(data.getMonth())+1) + "/" + data.getFullYear() + '<br></br><b>Hora: ' + result_time, {closeButton: true, timeOut: 50000, progressBar: true, allowHtml: true, positionClass: 'toast-top-center'});																															
												}
												else {
													if (countId == 4) {
														countId = 0;
														authenticate = 0;
														saveIdUser = 0;
													}
													else {
														countId++;
														console.log (countId);
													}													
												}													
											}
											if (result == "FAULT") {
												toastr.optionsOverride = 'positionclass = "toast-center-center"';
												toastr.options.positionClass = 'toast-center-center';
												setTimeout(toastr.remove(), 5000);
												toastr.success('<b>ERRO AO GRAVAR REGISTRO NA BASE DE PRESENÇA</b>', {closeButton: true, timeOut: 50000, progressBar: true, allowHtml: true, positionClass: 'toast-top-center'});
											}
										}
									})
								}
							})
						case "3":
							if (authenticate == 0) {
								toastr.optionsOverride = 'positionclass = "toast-center-center"';
								toastr.options.positionClass = 'toast-center-center';
								setTimeout(toastr.remove(), 5000);
								if (authenticate == 1) {
									toastr.success('<b>Pessoa NÃO Identificada ou ambiente com baixa luminosidade - Tente novamente</b>', {closeButton: true, timeOut: 50000, progressBar: true, allowHtml: true, positionClass: 'toast-top-center'});										
								}							}							
							break;
						case "606":
							if (authenticate == 0) {
								toastr.optionsOverride = 'positionclass = "toast-center-center"';
								toastr.options.positionClass = 'toast-center-center';
								setTimeout(toastr.remove(), 5000);
								if (authenticate == 1) {
									toastr.success('<b>Pessoa NÃO Identificada ou ambiente com baixa luminosidade - Tente novamente</b>', {closeButton: true, timeOut: 50000, progressBar: true, allowHtml: true, positionClass: 'toast-top-center'});										
								}							}							
							break;
						case "605":
							toastr.optionsOverride = 'positionclass = "toast-center-center"';
							toastr.options.positionClass = 'toast-center-center';
							setTimeout(toastr.remove(), 5000);
							toastr.success('<b>Foto detectada -> A imagem não foi tirada ao vivo</b>', {closeButton: true, timeOut: 50000, progressBar: true, allowHtml: true, positionClass: 'toast-top-center'});										
							break;
						default:
							if (authenticate == 0) {
								toastr.optionsOverride = 'positionclass = "toast-center-center"';
								toastr.options.positionClass = 'toast-center-center';
								setTimeout(toastr.remove(), 5000);	
								if (authenticate == 1) {
									toastr.success('<b>Pessoa NÃO Identificada ou ambiente com baixa luminosidade - Tente novamente</b>', {closeButton: true, timeOut: 50000, progressBar: true, allowHtml: true, positionClass: 'toast-top-center'});										
								}							}							
						break;
					}
				},
				dataType: "json",
				contentType : "application/json"
			});
		}

		function takeSnapShot(){
			//Captura elemento de vídeo
			video = document.querySelector("#video-teste");
			
			//Criando um canvas que vai guardar a imagem temporariamente
			canvas = document.createElement('canvas');
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;
			ctx = canvas.getContext('2d');
			
			//Desenhando e convertendo as dimensões
			ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
			
			//Criando o JPG
			dataURI = canvas.toDataURL('image/jpeg'); //O resultado é um BASE64 de uma imagem.
			document.querySelector("#base_img").value = dataURI;
			dataURI = dataURI.substring(23);
			Confirma();
		}
		
		function encodeImageFileAsURL(element) {
			file = element.files[0];
			reader = new FileReader();
			reader.onloadend = function() {
				console.log('RESULT', reader.result);
			}
			reader.readAsDataURL(file);
		}
		
		function Mensagem_Retorno(msg) {
			Swal.fire({
			  icon: 'error',
			  title: '',
			  text: msg,
			});			
		}
		
		function getIp(callback) {
			
			function response(s) {
				callback(window.userip);

				s.onload = s.onerror = null;
				document.body.removeChild(s);
			}

			function trigger() {
				window.userip = false;

				s = document.createElement("script");
				s.async = true;
				s.onload = function() {
					response(s);
				};
				s.onerror = function() {
					response(s);
				};

				s.src = "https://l2.io/ip.js?var=userip";
				document.body.appendChild(s);
			}

			if (/^(interactive|complete)$/i.test(document.readyState)) {
				trigger();
			} else {
				document.addEventListener('DOMContentLoaded', trigger);
			}
		}

		getIp(function (ip) {
			console.log(ip);
			//alert("IP: " + ip); 
		});
	</script>
</head>
<body onload="loop()">
	<div class="container-contact100">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<button id="button-camera" hidden></button>
		<video id="video-teste" style="max-width:100%;height:auto;"></video>
		<sub id="video-mensagem-erro"></sub>
		<script>						
			document.querySelector('#button-camera').addEventListener('click', window.onload=function () {
			  navigator.mediaDevices.getUserMedia({video: true})
			  .then(function (mediaStream) {
				  video = document.querySelector('#video-teste');
				  video.style.display = 'block';
				  video.srcObject = mediaStream;
				  video.play();
			  })
			  .catch(function (err) {
				document.querySelector('#video-mensagem-erro').innerText = 'Não há permissões para acessar a webcam';
			  })
			})
		</script>
		<img onchange="encodeImageFileAsURL(this)" id="img_preview" src="" style="max-width:100%;height:auto;"></img>
		<input type="text" id="base_img" style="max-width:100%;height:auto;" hidden></input>
		<!--<button class="contact100-form-btn"type="button" onclick="takeSnapShot()" hidden>Registrar Presença</button>!-->
		<div style="position: relative" class="margin">
		  <video onplay="onPlay(this)" id="inputVideo" autoplay muted></video>
		  <canvas id="overlay" />
		</div>	
	</div>
</body>
</html>
