function test () {
    //create an object to send the form data
    let data = new FormData() //
    //data.append(`input_element_id`,document.getElementById(`input_element_id`).value)
    data.append(`like`,document.getElementById(`like`).value)
    data.append(`dislike`,document.getElementById(`dislike`).value)

    //send the form data
    fetch("search_results.php", //the page to send the input to -- the relative according to php
        {
        method: "POST",
        body: data
        }
    )
    //---------------------------------not mine
    .then((result) => {
        if (result.status != 200) { throw new Error("Bad Server Response"); }
        return result.text();
      })
     
    // (D) SERVER RESPONSE
    .then((response) => {
        console.log(response);
      })
    //---------------------------------not mine

    //prevent form from submiting
    return false;
}

function change_value_like () {
    document.getElementById(`like`).value=`like`;
}