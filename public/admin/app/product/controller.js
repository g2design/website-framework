app.controller('productsCtrl', function ($scope, $http) {
	$scope.showCreateForm = function () {

		// clear form
		$scope.clearForm();

		// change modal title
		$('#modal-product-title').text("Create New Product");

		// hide update product button
		$('#btn-update-product').hide();

		// show create product button
		$('#btn-create-product').show();



	};

	// clear variable / form values
	$scope.clearForm = function () {
		$scope.id = "";
		$scope.name = "";
		$scope.description = "";
		$scope.price = "";
	};

	// create new product
	$scope.createProduct = function () {

		$http({
			method: 'POST',
			data: {
				'name': $scope.name,
				'description': $scope.description,
				'price': $scope.price
			},
			url: 'api/product/create.php'
		}).then(function successCallback(response) {

			// tell the user new product was created
			Materialize.toast(response.data, 4000);

			// close modal
			$('#modal-product-form').modal('close');

			// clear modal content
			$scope.clearForm();

			// refresh the list
			$scope.getAll();
		});
	}
});