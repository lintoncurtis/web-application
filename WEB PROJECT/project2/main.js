/*var orders = document.querySelector("#display");
console.log(orders.children);
var orders = document.getElementById("display");
orders.children;
var clone = orders.cloneNode(false);

<?php

?>
console.log(clone);
nextElementSibling;
querySelector("#display");
+=clone;


var btns = document.querySelectorAll('#display ');

btns.forEach(function(btn){
	btn.addEventListener('click', function(e){
	 const li = e.target.parentElement;
	 li.parentNode.removeChild(li);
	})
})



*/


function addItem(){
	var addItem = document.querySelector("#add");
	addItem.addEventListener('click', function(e){
		//console.log(e.target);
		//console.log(e);

		const order = document.querySelector("#display");
		var nextOrder = order.cloneNode;
		order.parentNode.addChild(nextOrder);

	});

}