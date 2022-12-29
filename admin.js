function click_admin(){
    $.ajax({
        type: "POST",
        url: "administator.php",
    }).done(function(msg) {
        alert("Post sent" + msg);
    });
    console.info("Test admin javascript");
}
