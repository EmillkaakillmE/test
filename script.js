$(function () {
	
	//region Оформление input
	let dateInputs = $('input.input-date');
	dateInputs.datepicker({
		dateFormat: "dd.mm.yyyy",
		position: 'bottom left',
		clearButton: 'Очистить',
		constrainInput: true,
		onSelect(formattedDate, date, inst) {
			inst.el.setAttribute('value', formattedDate)
			inst.hide();
		},
	});
	$('input, select')
		.on('keyup', function () {
			this.setAttribute('value', this.value)
		})
		.on('focus', function () {
			$(this).removeClass('error');
			$(this).siblings('.input-error').fadeOut();
		})
	$(".sum").inputmask({
		alias: "currency",
		prefix: '',
		placeholder: '',
		groupSeparator: ' ',
		max: 3000000,
		rightAlign: false,
		digitsOptional: true,
		allowMinus: false,
	});
	
	
	$(".percent").inputmask({
		alias: "percentage",
		prefix: '',
		integerDigits: 3,
		max: 100,
		digitsOptional: true,
		digits: 2,
		radixPoint: ',',
		placeholder: ' ',
		rightAlign: false,
		allowMinus: false,
	}).on('input', function () {
		let unmask = $(this).inputmask('unmaskedvalue');
		if (unmask) {
			let intValue = +unmask.replace(',', '.')
			if (intValue >= 100) {
				this.value = 100
			}
		}
	}).on('blur', function () {
		let unmask = $(this).inputmask('unmaskedvalue');
		if (unmask) {
			let intValue = +unmask.replace(',', '.')
			if (intValue <= 0) {
				this.value = false
			}
		}
	});
	
	$('.checkbox label').on('click', function (event) {
		const checkboxInput = $(this).find('input[type=checkbox]');
		const $this = $(this);
		if ($this.hasClass('active')) {
			checkboxInput[0].checked = false
			$this.removeClass('active')
		} else {
			checkboxInput[0].checked = true
			$this.addClass('active')
		}
		event.preventDefault();
	})
	
	
	//endregion
	
	
	//region Валидация
	
	let msgParent = document.createElement("div");
	msgParent.innerText = 'Срок вклада не может быть ';
	msgParent.style.display = 'none';
	msgParent.className = 'input-error';
	
	$('.form__main').on('submit', function (event) {
		event.preventDefault();
		const formInputs = this.elements;
		let data = {};
		for (let item of formInputs) {
			if (item.name === '')
				continue
			
			switch (item.name) {
				case 'startDate':
					if (item.value !== '') {
						data[item.name] = item.value;
						$(item).addClass('success');
						$(item).removeClass('error');
						
					} else {
						let msg = msgParent.cloneNode(true);
						msg.innerText = 'Заполните поле'
						$(item).closest('label').append(msg);
						$(msg).fadeIn();
						$(item).removeClass('success');
						$(item).addClass('error');
						data[item.name] = false;
					}
					break;
				case 'sum':
					data[item.name] = Validate(1000, 3000000, 'меньше 1 000', 'больше 3 000 000', item, 'Сумма вклада не может быть ');
					break;
				case 'sumAdd':
					let checked = formInputs['fill-per-month'].checked;
					if (!checked) {
						data[item.name] = 0;
						$(item).removeClass('error');
						$(item).siblings('.input-error').fadeOut();
					} else {
						data[item.name] = Validate(1000, 3000000, 'меньше 1 000', 'больше 3 000 000', item, 'Сумма вклада не может быть ');
					}
					break;
				case 'term':
					let selectValue = formInputs['year-month'].value;
					let beginText = 'Срок вклада не может быть ';
					if (selectValue === 'month') {
						data[item.name] = Validate(1, 60, 'меньше 1 месяца', 'больше 60 месяцев', item, beginText);
					} else {
						data[item.name] = Validate(1, 5, 'меньше 1 года', 'больше 5 лет', item, beginText);
					}
					break;
				case 'year-month':
					data[item.name] = item.value;
					break;
				case 'percent':
					data[item.name] = Validate(3, 100, 'меньше 3%', 'больше 100%', item, 'Процентная ставка не может быть ');
					break;
				case 'fill-per-month':
					data[item.name] = item.checked;
					break;
			}
			
		}
		
		let canSend = true;
		for (const [name, value] of Object.entries(data)) {
			if (value === false && name !== 'fill-per-month')
				canSend = false;
		}
		if (canSend) {
			$('.container_small').fadeOut()
			$.ajax({
				url: "calc.php",
				type: "POST",
				data: data,
				cache: false,
				dataType: 'json',
				success: (data) => {
					if (data.CODE === 500)
						window.location.reload()
					else {
						let resultMsg1 = 'По формуле: ₽ '
						let resultMsg2 = 'По моей формуле: ₽ '
						$('.result .result__sum').html(resultMsg1 + data.RESULT_1);
						$('.result .result__sum2').html(resultMsg2 + data.RESULT_2);
						$('.container_small').fadeIn()
					}
				}
			});
		}
		
	})
	//endregion
});


function Validate(from, to, textFrom, textTo, item, beginText) {
	let msgParent = document.createElement("div");
	msgParent.innerText = beginText;
	msgParent.style.display = 'none';
	msgParent.className = 'input-error';
	let result = false;
	
	
	if (item.value < from || item.value > to) {
		let msg = msgParent.cloneNode(true);
		$(item).removeClass('success');
		$(item).addClass('error');
		msg.innerText += item.value < 1000 ? textFrom : textTo
		$(item).closest('label').append(msg);
		$(msg).fadeIn();
	} else {
		result = item.value;
		$(item).addClass('success');
		$(item).removeClass('error');
	}
	
	if (item.name === 'percent' && item.value) {
		let unmask = +$(item).inputmask('unmaskedvalue');
		let isInt = Number.isInteger(unmask)
		if (!isInt) {
			let msg = msgParent.cloneNode(true);
			$(item).removeClass('success');
			$(item).addClass('error');
			msg.innerText += 'не целым числом'
			$(item).closest('label').append(msg);
			$(msg).fadeIn();
			return false
		} else {
			result = unmask;
		}
		
	}
	
	return result;
}