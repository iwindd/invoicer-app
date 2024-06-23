$(document).ready(function(){
  const elements =  $('[data-format]');

  elements.map((index) => {
    const element = $(elements[index]);
    const formatType = element.attr('data-format');
    
    if (ff[formatType]){
      element.html(ff[formatType](element.html()))
    }else{
      console.error('not found formatter : '+formatType);
    }
  })
})