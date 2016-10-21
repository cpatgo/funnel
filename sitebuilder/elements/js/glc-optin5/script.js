$(function(){
    // Click event function to open modal (active)
    $(".button").on("click", function(){
          $(".mask").addClass("active"); //Finds .mask class and adds the active class
    });

    // Função para fechar o modal.
    function closeModal(){
      $(".mask").removeClass("active"); //Remove the active class
    }

    // Function to close the modal screen
    $(".close, .mask").on("click", function(){
          closeModal();
    });

    // Closes the modal with the button within the content
    $(".content-button-close").click(function(){
          closeModal();
    });
});