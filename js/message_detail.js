window.onload = function() {
	var ret = document.getElementById('return');
	var del = document.getElementById('delete');
	ret.onclick = function() {
		history.back();
	};

	del.onclick = function() {
		if(confirm('您确定要删除次条短信吗？')){
			location.href='?action=delete&id='+this.name;
		}
	};
};