function updateContent(panelID, location, loadingText, parameters, userInputs)
{
    //alert('UPDATE FUNCTION CALLED');
    //Retrieving Your Content<span class="mif-loop2 icon"></span>
    edit(panelID + '_loadingAccordion_0', loadingText + '<span class="mif-loop2 icon"></span>');
    //updatePanel_loadingAccordion_0
    if(document.getElementById(panelID + '_loadingAccordion'))
    {
        $('#' + panelID).hide();
        $('#' + panelID + '_loadingAccordion').show();
    }
    if(userInputs)
    {
        for(var counter = 0; counter < userInputs.length; counter++)
        {
            if (document.getElementById(userInputs[counter]) + '_Input')
            {
                parameters[userInputs[counter]] = document.getElementById(userInputs[counter] + '_Input').value;
                if (document.getElementById(userInputs[counter]) + '_Error')
                    $('#' + userInputs[counter] + '_Error').hide();
            }
        }
    }
    if(parameters)
        parameters = JSON.stringify(parameters);
    /*if(userInputs)
    {
        alert('USER INPUTS: ' + userInputs);
        userInputs = JSON.parse(userInputs);
        for(var counter = 0; counter < userInputs.length; counter++)
            alert('USER INPUTS: ' + userInputs[counter]);
    }*/
    $.ajax
    ({
        //url: "services.php",
        url: location,
        type:"POST",
        data: parameters
        /*{
            test: '5',
            test2: '10'
        }*/,
        dataType: "JSON",
        success: function(result)
        {
            if(result['addToHTML'])
            {
                //var currentChildren = document.getElementById(panelID).children;
                //alert('BEFORE ADDING OF CHILD');
                //currentChildren[currentChildren.length] = result['addToHTML'];
                document.getElementById(panelID).innerHTML = document.getElementById(panelID).innerHTML + result['addToHTML'];
                //document.getElementById(panelID).appendChild(result['addToHTML']);
                //alert('AFTER ADDING OF CHILD');
                //document.getElementById(panelID).innerHTML = document.getElementById(panelID).innerHTML + result['addToHTML'];
            }
            else if(result['html'])
            {
                document.getElementById(panelID).innerHTML = result['html'];
            }
            if(result['updateContent'])
            {
                updateContent(result['updateContent']['panel'], result['updateContent']['page'], result['updateContent']['loadingMessage'],
                result['updateContent']['parameters']);
            }
            if(result['success'])
                displaySuccessNotification(result['success']['title'], result['success']['content']);
            if(result['error'])
            {
                displayErrorNotification(result['error']['title'], result['error']['content']);
                if(result['error']['control'] && document.getElementById(result['error']['control'] + '_Error'))
                {
                    document.getElementById(result['error']['control'] + '_Error').innerHTML = '<div class="fg-white">' + result['error']['content'] + '</div>';
                    $('#' + result['error']['control'] + '_Error').show();
                }
            }
            if(result['toggle'])
            {
                if(document.getElementById(result['toggle']['control']))
                {
                    document.getElementById(result['toggle']['control']).click();
                    if(result['toggle']['text'])
                        document.getElementById(result['toggle']['control']).innerHTML = result['toggle']['text'];
                }
            }
            $('#' + panelID + '_loadingAccordion').hide();
            $('#' + panelID).show();
        },
        error: function(errorMessage)
        {
            //alert('ERROR MESSAGE REVISED: ' + errorMessage);
        }
    });
    /*
     $.ajax({
     url: "text.php",
     type: "POST",
     data: {
     amount: $("#amount").val(),
     firstName: $("#firstName").val(),
     lastName: $("#lastName").val(),
     email: $("#email").val()
     },
     dataType: "JSON",
     success: function (jsonStr) {
     $("#result").text(JSON.stringify(jsonStr));
     }
     });
    */
}
function displayErrorNotification(title, content)
{
    $.Notify({type: 'alert', caption: title, content: content, icon: "<span class='mif-cross'></span>", shadow: true, timeout: 6000});
}
function displaySuccessNotification(title, content)
{
    $.Notify({type: 'success', caption: title, content: content, icon: "<span class='mif-checkmark'></span>", shadow: true, timeout: 6000});
}
function edit(id, newText)
{
    if(document.getElementById(id))
        document.getElementById(id).innerHTML = newText;
}
function editClassNameForMultipleElements(id, newClassName, numberOfElements)
{
    if(numberOfElements)
        for(var k = 1; k <= numberOfElements; k++)
            if(document.getElementById(id + '_' + k))
                document.getElementById(id + '_' + k).className = newClassName;
}
function appendClassName(id, appendClassName)
{
    if(document.getElementById(id))
        document.getElementById(id).className = document.getElementById(id).className + appendClassName;
}
function update(element, value)
{
    if(document.getElementById(element))
        document.getElementById(element).value = value;
}