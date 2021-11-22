<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
			content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="assets/datepicker/css/datepicker.min.css">

	<!--jquery-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.6/jquery.inputmask.bundle.min.js"></script>
	<!--end jquery-->

	<!--slim select-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.css" rel="stylesheet"/>
	<!--end slim select-->

	<title>Депозитный калькулятор</title>
</head>
<body>

<div class="wrapper">
	<header class="header">
		<div class="container container-wide">
			<div class="header__wrapper">
				<div class="logo">
					<div class="logo__img">
						<a href="/">
							<img src="img/logo.png" alt="Deposit Calculator">
						</a>
					</div>
					<div class="logo__label">
						<a href="/">
							<span>Deposit Calculator</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</header>
	<main>
		<div class="container">
			<div class="form">

				<div class="form__titles">
					<div class="form__big-title">
						<span>Депозитный калькулятор</span>
					</div>
					<div class="form__small-title">
						<span>
							Калькулятор депозитов позволяет рассчитать ваши доходы после внесения суммы на счет в банке
							по опредленному тарифу.
						</span>
					</div>
				</div>

				<div class="form__body">
					<form class="form__main" action="#" method="post">
						<div class="form__row">
							<div class="form__div">
								<label for="open-date">
									<input
											  id="open-date"
											  class="input-date"
											  type="text"
											  name="startDate"
											  value=""
											  readonly
									>
									<span class="input-placeholder">Дата откытия</span>
								</label>
							</div>

							<div class="form__div">
								<label for="term-date">
									<input
											  id="term-date"
											  class="select-input"
											  type="text"
											  name="term"
											  value=""
									>
									<span class="input-placeholder">Срок вклада</span>
									<select name="year-month" class="select year-month" id="year-month">
										<option selected value="month">Месяц</option>
										<option value="year">Год</option>
									</select>
								</label>
							</div>
						</div>

						<div class="form__row">
							<div class="form__div">
								<label for="sum">
									<input
											  id="sum"
											  class="sum"
											  type="text"
											  name="sum"
											  value=""
									>
									<span class="input-placeholder">Сумма вклада</span>
								</label>
							</div>
							<div class="form__div">
								<label for="percent">
									<input
											  id="percent"
											  class="percent"
											  type="text"
											  name="percent"
											  value=""
									>
									<span class="input-placeholder">Процентная ставка, % годовых</span>
								</label>
							</div>
						</div>

						<div class="form__row">
							<div class="form__div checkbox">
								<label class="checkbox__label" for="fill-per-month">
									<input
											  id="fill-per-month"
											  class="fill-per-month checkbox__input"
											  type="checkbox"
											  name="fill-per-month"
									>
									<span>Ежемесячное пополнение вклада</span>
								</label>
							</div>
							<div class="form__div">
								<label for="sumAdd">
									<input
											  id="sumAdd"
											  class="sum"
											  type="text"
											  name="sumAdd"
											  value=""
									>
									<span class="input-placeholder">Сумма пополнения вклада</span>
								</label>
							</div>
						</div>

						<div class="form__row submit">
							<div class="form__div">
								<button type="submit"><span>Рассчитать</span></button>
							</div>
						</div>

					</form>
				</div>
			</div>
			<div class="container_small" style="display: none">
				<div class="separator"></div>

				<div class="result">
					<div class="result__title">
						Сумма к выплате
					</div>
					<div class="result__sum">

					</div>
					<div class="result__sum2">

					</div>
				</div>

			</div>
			<div class="res"></div>
		</div>
	</main>
	<footer></footer>
</div>


<script src="assets/datepicker/js/datepicker.min.js"></script>
<script src="script.js"></script>
</body>
</html>