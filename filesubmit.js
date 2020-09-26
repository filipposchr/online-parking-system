$('document').ready(function() {
//Fortwsi arxeiou kml
	$("#file-form").submit(function(evt){ //koumpi apo to managedb
		evt.preventDefault();

		var theFile = $('#file').get(0).files[0];
		var data = new FormData($(this)[0]);
		var check = $('#file').get(0).files.length;

		if(check == 0) {
			return;
		}

		$.ajax({
			type : 'POST',
			url  : 'upload.php',
			data : data,
			processData: false,
			contentType: false,
			cache:false,
			success : function(response){
				if(response=="Ok"){
					console.log(response);
					alert("Το αρχείο φορτώθηκε επιτυχώς.");
					document.getElementById("bt-file").innerHTML = "Φόρτωση αρχείου";
					document.getElementById("result").innerHTML = "Φορτωμένο αρχείο: " + theFile.name;
					$('#file').val("");
					parsing();
				}
				else {
					alert(response);
					document.getElementById("bt-file").innerHTML = "Φόρτωση αρχείου";
					document.getElementById("result").innerHTML = "";
					$('#file').val("");
					//console.log($('#file').get(0).files);
				}
			},
			error: function(err) {
				console.log(err);
				alert("Aποτυχία φόρτωσης αρχείου.");
				document.getElementById("bt-file").innerHTML = "Φόρτωση αρχείου";
				document.getElementById("result").innerHTML = "";
				$('#file').val("");
				//console.log($('#file').get(0).files);
			}
		});
	});
});

function ChangeText() {
	if ($('#file').get(0).files.length == 0) {
		return;
	}
	document.getElementById("bt-file").innerHTML = $('#file').get(0).files[0].name;
	document.getElementById('bt-hid').click();
}

function parsing() {
	$("#loader").html('<img src="images/ajax-loader.gif" /> &nbsp; Φόρτωση δεδομένων στη βάση ...');

	$.ajax({
		type : 'POST',
		url  : 'parser.php',
		success : function(response){
			if(response=="Ok"){
				console.log(response);
				$("#loader").empty();
				alert('Τα δεδομένα φορτώθηκαν επιτυχώς στη Βάση Δεδομένων.');
			}
			else {
				alert("Προέκυψε Σφάλμα Φόρτωσης");
				$("#loader").empty();
			}
		},
		error: function(err) {
			console.log(err);
			$("#loader").empty();
			alert("Aποτυχία φόρτωσης δεδομένων στη Βάση Δεδομένων.");
		}
	});

}
//Adiasma pinakwn apo tin vasi dedomenwn
function deleteDB() {
	var r = confirm("Θέλετε σίγουρα να διαγράψετε τα υπάρχοντα δεδομένα;");
	if (r == true) {
		console.log("Deleted");

		$.ajax({
			type: "POST",
			url: "delete_db.php",
			success: function(response) {
				alert(response);
			},
			error: function(xhr,textStatus,errorThrown) {
				alert('Αποτυχία διαγραφής');
			}
		});

	} else {
		console. log("Not Deleted");
		return;
	}
}