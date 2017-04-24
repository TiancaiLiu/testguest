//等待网页加载完毕再执行
window.onload = function() {
	var faceimg = document.getElementById('faceimg');
	faceimg.onclick = function() {
		window.open('face.php','face','width=400px,height=400px,top=0,left=0,scrollbars=1');
	}

	//表单验证
	var fm = document.getElementsByTagName('form')[0];
	fm.onsubmit = function() {
		//用户名验证
		if(fm.username.value.length < 2 || fm.username.value.length > 20) {
			alert('用户名不得少于2位或者大于20位');
			fm.username.value = '';
			fm.username.focus();
			return false;
		}
		if(/[<>\'\"\ \ ]/.test(fm.username.value)) {
			alert('用户名不得包含非法字符');
			fm.username.value = '';
			fm.username.focus();
			return false;
		}
		//密码验证
		if(fm.password.value.length < 6) {
			alert('密码不得少于6位');
			fm.password.value = '';
			fm.password.focus();
			return false;
		}
		if(fm.password.value != fm.notpassword.value) {
			alert('密码和确认密码必须一致');
			fm.notpassword.value = '';
			fm.notpassword.focus();
			return false;
		}
		//密码提示和回答
		if(fm.question.value.length < 2 || fm.question.value.length > 20) {
			alert('密码提示不得少于2位或者大于20位');
			fm.question.value = '';
			fm.question.focus();
			return false;
		}
		if(fm.answer.value.length < 2 || fm.answer.value.length > 20) {
			alert('密码提示不得少于2位或者大于20位');
			fm.answer.value = '';
			fm.answer.focus();
			return false;
		}
		if(fm.question.value == fm.answer.value) {
			alert('密码提示和密码回答不能想相同');
			fm.answer.value = '';
			fm.answer.focus();
			return false;
		}
		//验证邮箱
		if(!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(fm.email.value)) {
			alert('邮箱格式不正确');
			fm.email.value = '';
			fm.email.focus();
			return false;
		}
		//验证QQ
		if(fm.qq.value != '') {
			if(!/^[1-9]{1}[0-9]{4,9}$/.test(fm.qq.value)) {
				alert('QQ号码不正确');
				fm.qq.value = '';
				fm.qq.focus();
				return false;
			}
		}
		//网址验证
		if(fm.url.value != '') {
			if(!/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(fm.url.value)) {
				alert('个人主页地址不正确');
				fm.url.value = '';
				fm.url.focus();
				return false;
			}
		}
		//验证码验证
		if(fm.vcode.value.length != 4) {
			alert('验证码必须是4位');
			fm.vcode.value = '';
			fm.vcode.focus();
			return false;
		}

		return true;
	};
};