/**
 * Created by 1570314 on 1/2/2018.
 */
$(function() {
    //Search function
    $("#searchButton").on("click",function(){
        var searchText = $("#searchButton").val();
        $.ajax({
            method: "POST",
            url: "server.php",
            data: { searchText: "searchText",action:"search"}
        })
        .done(function( response ) {
            var trList = "";
            $.each( response, function( i, obj ){
                trList += "<tr id='tr'"+obj['id']+">"+generateUserInfoTD(obj)+"</tr>";
            });
            $("#userListTable tbody").empty().html(trList);

        });
    });

    function generateUserInfoTD(obj){
        return "<td class='name'>"+ obj['name'] + "</td><td class='age'>"+ obj['age'] + "</td>" +
        "<td><i data-id="+obj['id']+" class='fa fa-pencil-square-o editIcon' aria-hidden='true'></i></td>";
    }

    $(document).on("click",".editIcon",function () {
        var id = this.attr("data-id");
        $.ajax({
            method: "POST",
            url: "server.php",
            data: { id: id,action:"searchById"}
        }).done(function( response ) {
            $("#age").val(response.age);
            $("#name").val(response.name);
            $("#hiddenUserId").val(response.id);

            $('#userModal').modal('show');
        });
    });

    $(document).on("click","#saveButton",function () {
        var userId =  $("#hiddenUserId").val();
        var dataObj = { id: userId,
            name:$("#name").val(),
            age:$("#age").val(),
            action:"edit"};
        $.ajax({
            method: "POST",
            url: "server.php",
            data: dataObj
        }).done(function( response ) {
           if(response == "successful"){
               $('#myModal').modal('hide');
               $("tr"+ userId).empty().html(generateUserInfoTD(dataObj));
           }else {
                alert("Fail ! Please try again.");
           }
        });
    });

});