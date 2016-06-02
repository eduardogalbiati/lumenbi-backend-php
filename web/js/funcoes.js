function scrollToTop(){
  $('html,body').animate({
    scrollTop: '0'
  }, 200);
}

function scrollToContainerTop(){
  $('html,body').animate({
    scrollTop: $('header').height()+$('.navbar').height()
  }, 200);
}
function trataJson(json){
  try{
    var obj = jQuery.parseJSON(json);
    for (var i=0;i<obj.campos_invalidos.length;i++){
      $('#'+obj.campos_invalidos[i]).closest(".row-fluid").addClass('error');
    }
  } catch(err){
    scrollToTop();
    $('#div_erro_c').show();
    $('#div_erro').html(json); 
  }
}


$(document).ready(function(){
	
  $('*[rel="popover"]').popover({
    html: true
  });
  $('*[rel="tooltip"]').tooltip();
  //$('input, textarea').placeholder();
  $('.trata-nomes').on('blur',function(){
    $(this).val( $(this).val().replace(/[^A-Za-z ]/g, '').toUpperCase() )
  });
  $('.mask-date').mask("99/99/9999");
  $('.mask-hour').mask("99:99");
  $('.mask-cep').mask("99999-999");
  $('.mask-chassi').mask("*****************");  
  $('.mask-ci').mask("**************");  
  $('.mask-apolice').mask("9999999999999999");  
  $('.mask-placa').mask("aaa-9999");
  $('.mask-numerico').mask("0-9");
  $('.mask-coeficiente').mask("999,99");
  $(".mask-cnpj").mask("99.999.999/9999-99");
  $('.mask-cpf').mask("999.999.999-99");
  $(".mask-porcentagem").mask("99%"); 
  $('.mask-valor').priceFormat({
    prefix: 'R$ ',
    centsSeparator: ',',
    thousandsSeparator: '.'
  });
  $('.mask-tel').mask("(99) 9999-9999?9")
  .on('focusout', function (event) {
    var target, phone, element;
    target = (event.currentTarget) ? event.currentTarget : event.srcElement;
    phone = target.value.replace(/\D/g, '');
    element = $(target);
    element.unmask();
    if(phone.length > 10) {
      element.mask("(99) 99999-999?9");
    } else {
      element.mask("(99) 9999-9999?9");
    }
  
  });


var $contactForm = $('#frm_cadastro');
$contactForm.on('submit', function(ev){
    ev.preventDefault();

 var button = $('#btn_cadastro');
  var div_alert = $('#div_alert');
  $.ajax({
    url: "/"+$('#module').val()+"/crud/editAction",
    dataType: 'html',
    data: $('#frm_cadastro').serialize(),
    type: "POST",
    beforeSend: function() {
      button.button('loading');
    },
    success: function(data) {
      convertResponseInAlert(data, div_alert);
    },
    error: function(data) {
      convertResponseInAlert(data.responseText, div_alert);
    },
    complete: function(data){
      button.button('reset');
      scrollToTop('1000');

    }
  });
});
});

function scrollToElement(element){
  $("html, body").animate(
    { scrollTop: (element.offset().top - 30) }, '1000'
  );
}

function convertResponseInAlert(data, div) 
{ 
  try {
    var json = JSON.parse(data.trim());
  } catch (err) {
    div.html('<h4><i class="fa fa-times-circle"></i> Ops!</h4>Ocorreu um erro inesperado. (<i>' + data + '</i>)</div>').removeClass().addClass('alert alert-danger');
    return;
  }
  var classe;
  var mensagem;
  console.log(json);
  for (var x in json){
    if (json[x].status == 0) {
      classe = 'alert alert-danger';
      mensagem = '<i class="fa fa-times-circle"></i> <strong>Erro!</strong> '+json[x].alerta+'';
    } else if (json[x].status == 1) {
      classe = 'alert alert-success';
      mensagem = '<span class="pull-left"><i class="fa fa-check-circle"></i> <strong>Sucesso!</strong> '+json[x].alerta+'</span>';
      mensagem += '<span class="pull-right"><button type="button" class="btn btn-xs btn-default">Voltar à listagem</button>';
      mensagem += '  <button type="button" class="btn btn-xs btn-success">Novo Registro</button></span>';
      mensagem += '<div class="clearfix"></div>';
    } else if (json[x].status == 2) {
      classe = 'alert alert-warning';
      mensagem = '<i class="fa fa-warning"></i> <strong>Atenção!</strong> '+json[x].alerta+'';
    } else {
      classe = 'alert alert-danger';
      mensagem = '<i class="fa fa-times-circle"></i> <strong>Ops!</strong> Requisição indefinida';
    }
  }
 div.show().html(mensagem).removeClass().addClass(classe);
}

function search(){
   $.ajax({
      type: "get",
      url: "/"+$('#module').val()+"/crud/listAction",
      data: $('#frm_filtro').serialize(),
      success: function(data)
      {
       $('#list_container').html(data);
      },
      error: function(data) {
       $('#list_container').html(data.responseText);
      }
    });
}

function loadPage(page){
  $('#page').val(page);
  search();
}
function loadPageNext(){
  $('#page').val(parseInt($('#page').val())+1);
  search();
}
function loadPageBefore(){
  $('#page').val(parseInt($('#page').val())-1);
  search();
}