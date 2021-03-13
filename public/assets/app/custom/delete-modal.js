function deleteModal(titleString = '', paragraphString = '', id = -1, url = '') {
    Swal.fire({
		title: 'Είστε σίγουρος?',
		text: "Διαγραφή " + titleString + " με id: " + id,
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Ναι, διαγραφή!',
		cancelButtonText: 'Ακύρωση'
	  }).then((result) => {
		if (result.value) {
			$.ajax({
				url: BASE_URL + url + id,
				type: 'POST',
				data: {
					"_method": "DELETE"
				},
				dataType: 'json'
			})
			.done(function(res) {
				window.location.reload();
				Swal.fire(
					'Διαγραφή',
					paragraphString + ' διαγράφθηκε',
					'success'
				)
			})
			.fail(function(err) {
				console.log(err);
				Swal.fire(
					'Σφάλμα',
					paragraphString + ' δε διαγράφθηκε',
					'warning'
				)
			})

		}
	});
}