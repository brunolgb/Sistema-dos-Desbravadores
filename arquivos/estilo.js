function foco($input)
{	var inputFoco = document.getElementById($input);
	inputFoco.focus();
}
function converteJason(meuJason)
{
	var jason = JSON.parse(meuJason);
	var elemento = document.querySelector("#conselheiro");
	elemento.addEventListener("change", function (){
		var valor_elemento = elemento.value;
		// var efeito_elemento = valor_elemento.indexOf(" "); //vendo a posição do espaço
		// var valor_elemento = valor_elemento.substring(0,efeito_elemento); //retirando o que tem depois do espaço
		for (var i = 0; i <= 1000; i++)
		{
			if(jason.conselheiro[i] === valor_elemento)
			{
				var recebendoClasse = jason.classe[i];
				var buscaJasonClasse = recebendoClasse.indexOf("-");
				console.log(buscaJasonClasse);
				if (buscaJasonClasse > 0)
				{
					var valor_c1 = recebendoClasse.substring(0,buscaJasonClasse);
					var valor_c2 = recebendoClasse.substring(buscaJasonClasse+1);


					// recuperando os inputs
					var input_c1 = document.querySelector("#classe1");
					var input_c2 = document.querySelector("#classe2");

					// recuperando os labels
					var label_c1 = document.querySelector(".classe1");
					var label_c2 = document.querySelector(".classe2");

					// colocando nos inputs os valores obtidos
					input_c1.value = valor_c1;
					input_c2.value = valor_c2;

					// colocando nos labels os valores obtidos
					label_c1.innerHTML = valor_c1;
					label_c2.innerHTML = valor_c2;
				}
				else
				{
					var valor_c1 = recebendoClasse;


					// recuperando os inputs
					var input_c1 = document.querySelector("#classe1");
					var input_c2 = document.querySelector("#classe2");

					// recuperando os labels
					var label_c1 = document.querySelector(".classe1");
					var label_c2 = document.querySelector(".classe2");

					// colocando nos inputs os valores obtidos
					input_c1.value = valor_c1;
					input_c2.style.display = 'none';

					// colocando nos labels os valores obtidos
					label_c1.innerHTML = valor_c1;
					label_c2.style.display = "none";
				}
			
				// mostrando as classes input radio
				var classes = document.querySelector(".ocultarClasses");
				classes.classList.add("mostrarClasses");
			}
		}


	});
	
}

function aba()
{
	var elemento = document.querySelector(".template-requisitos");

	// funções para mostrar o elemneto
	function display()
	{
		elemento.classList.toggle("template-requisitos-mostrar-display");
	}
	function opacity()
	{
		elemento.classList.toggle("template-requisitos-mostrar-opacity");
	}
	

	// mostrando o elemento por tempo
	setTimeout(display,0);
	setTimeout(opacity,5);
}
function fechar()
{
	var elemento = document.querySelector(".template-requisitos");

	// funções para mostrar o elemneto
	function display()
	{
		elemento.classList.toggle("template-requisitos-mostrar-display");
	}
	function opacity()
	{
		elemento.classList.toggle("template-requisitos-mostrar-opacity");
	}
	

	// mostrando o elemento por tempo
	setTimeout(display,500);
	setTimeout(opacity,0);
}
function selecionar(numerador)
{
	// valores
	var valorReq = document.querySelectorAll("#reqRequisito")[numerador].innerHTML;
	var divMae = document.querySelectorAll(".maeRequisito")[numerador];
	// inputs
	var inputEscondido = document.querySelector("#nrequisito");
	var inputRequisito = document.querySelector("#requisito");

	// guardando nos inputs
	inputEscondido.value = divMae.getAttribute("id");
	inputRequisito.value = valorReq;
	fechar();
	
}

function monetario()
{
	var inputQuanto = document.querySelector("#valor");
	var value = inputQuanto.value;
	//verificando se colocaram o ponto no lugar da virgula
	var ponto = value.indexOf(".");
	if (ponto)
	{
		value = value.replace(".",",");
	}

	//verificando a posição da virgula
	var caracteres = value.indexOf(",");	

	//diminuindo os caracteres apos a virgula
	var verificando = value.length - caracteres;

	//mensagem de erro
	var erro = document.querySelectorAll("#erro")[1];

	//verificando esta em float
	if (caracteres < 0)
	{
		inputQuanto.value = value+",00";
	}
	else
	{
		if (verificando >= 2 && verificando <= 3)
		{
			inputQuanto.value = value;
			erro.innerHTML = null;
		}
		else
		{
			erro.innerHTML = "O valor inserido não é aceito. Verifique os centavos!";
			inputQuanto.focus();
		}
		
	}
}
function verificarData(valor)
{
	switch (valor) {
		case '#quando': var selecionador = 0 ; break;
		case '#termino': var selecionador = 1 ; break;
	}
	var inputData = document.querySelector(valor);

	var requisicao = new XMLHttpRequest();
	requisicao.open("GET", "validacao.php?data="+inputData.value,true);
	requisicao.onreadystatechange = function (){
		var resultado = requisicao.responseText;
		var carac_resultado = resultado.length;

		var span_erro = document.querySelectorAll("#erro")[selecionador];
		if (carac_resultado != 0)
		{
			//retirar as 3 primeiras letras;
			var texto = resultado.substring(3,carac_resultado);
			if (texto)
			{
				span_erro.innerHTML = "<p>"+texto+"</p>";
				inputData.focus();
			}
			else
			{
				span_erro.innerHTML = null;
			}
			
		}
	};
	requisicao.send();
}
function excluir (recebendo)
{
	var section = document.querySelectorAll("#controle")[recebendo];
	function retirar()
	{
		section.style.display = 'none';
	}
	var aside = document.querySelectorAll(".idRequisito")[recebendo];
	var inner = aside.innerHTML;
	var rSection = new XMLHttpRequest();
	rSection.open("GET", "excluir.php?idR="+inner,true);
	rSection.onreadystatechange = function (){
		var retorno = rSection.responseText;
		section.innerHTML = "<h1>"+retorno+"</h1>";
		setTimeout(retirar,2500);
		//<meta http-equiv='refresh' content='2'>
	};
	rSection.send();


}

function ativarMenu()
{
	var menu = document.querySelector(".menu");
	var nav = document.querySelector("nav");

	nav.classList.toggle("mostraMenu");
}
function verificarFeito(rec)
{
	var ve = new XMLHttpRequest();
	ve.open("GET", "verificarFeito.php?id="+rec, true);
	ve.send();
}
function visualizar()
{
	var formulario = document.querySelector("#envioFormulario");
	formulario.submit();
}
function acrescentar()
{
	
}