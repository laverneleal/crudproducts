// $(function(){
//     read();
// })

// function read(){
//     $.ajax({
//         method: "POST",
//         url: "read.php", 
        
//         success: function(data){
//             $("#data").html(data);
//         }
//     })
// })

$(document).ready(function() {
    $.ajax({
        method: "POST",
        url: "product.php", 
        data:{
            search : $('#search').val()
        },
        success: function(data){
            $("#data").html(data);
        }
    })
});

function myFunction(){
    $.ajax({
        method: "POST",
        url: "product.php", 
        data:{
            search : document.getElementById('search').value
        },                
        success: function(data){
           // alert(document.getElementById('search').value);
            $("#data").html(data);
        }
    })
}

   