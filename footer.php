<script src="/js/bootstrap.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
  crossorigin="anonymous">
</script>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui.js"></script>

<script type="text/javascript">
$(".hide-form").click(function() {
  $("#sign-up").toggleClass("hidden");
  $("#sign-in").toggleClass("hidden");
})

$("#diary").bind('input propertyChange', function() {
  $.ajax({
    method: "POST",
    url: "/updateDiary.php",
    data: {
      content: $("#diary").val()
    }
  }).done(function(msg) {
    $("#result").html(msg);
    $("#result").show();
    setTimeout(function() {
      $("#result").hide();
    }, 5000);
  });
})
</script>

</body>

</html>