// Template Maker Function
function htmlInjector(data, containerSelector, templateSelector) {
    let innersnippet = document.querySelector(templateSelector).innerHTML;
    let template = Handlebars.compile(innersnippet);
    let content = document.querySelector(containerSelector);
    content.innerHTML = template(data);
}



// htmlInjector(data,"#country-info","#country-info-template");


        


