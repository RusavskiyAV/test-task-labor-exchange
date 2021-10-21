Тестовое задание
================================================

Описание
--------

"Биржа рабов" (Древнее HR агентство). Биржа рабов позволяет покупать или брать в аренду рабов для различных задач:

- земледелие;
- скотоводство;
- работа по дому (уборка, приготовление пищи);
- работа в каменоломне;
- и др.

Для описания рабов используются характеристики:

- кличка;
- пол;
- возраст;
- вес;
- цвет кожи;
- где пойман/выращен;
- описание и повадки (например, любит играть с собакой);
- ставка почасовой аренды;
- стоимость.

Для успешного бизнеса необходимо спроектировать систему расчёта аренды рабов. Процесс аренды (сильно упрощён).

Описание системы аренды:

- пользователь находит подходящего раба в каталоге и переходит на страницу аренды раба.
- пользователь выбирает желаемое время аренды (например, с 01 июня 2016 14.00 по 05 июня 2016 20.00):
  - если аренда на выбранное время возможна, система оформляет аренду и выводит договор аренды с итоговой стоимостью.
  - если аренда невозможна, выводится информация о причинах.

Требования и ограничения:

- у покупателей (хозяев) бесконечное количество золота;
- рабы не могут работать больше 16 часов в сутки;
- рабочий день начинается с 00:00;
- время аренды округляется до часов в большую сторону:
  - раба арендуют с 11.30 до 13.00, то это 3 часа: 11, 12, 13;
  - раба арендуют с 12.00 до 12.30, то это 1 час - 12;
- При аренде на несколько дней в полных днях:
  - правило 16 часов не проверяется (всё на совести клиента);
  - считается, что заняты все 24 часа;
  - стоимость дня = стоимости 16 часов;
- При аренде на несколько дней в полных днях:
  - часы первого дня считаются с момента аренды и до конца дня;
  - часы последнего дня считаются с 00:00 до конца аренды;
  - правило 16 часов проверяется;
- Нельзя арендовать раба на выбранный период, если хотя бы один час в периоде уже занят;
- VIP клиенты имеют приоритет перед обычными и могут игнорировать занятые не VIP-ами часы;
- Если аренда невозможна, в причине должна быть подробная информация о перекрытии аренды по времени;
- В дальнейшем будут добавляться другие участники и проверки, код должен быть готов к развитию в этих направлениях:
  - несколько уровней VIP (бронза, серебро, золото);
  - "охрана", на которую можно арендовать круглосуточно.

Цель
----

Выяснить уровень владения ООП, навыки построения абстракций и знание принципов и инструментов разработки. Всё, что не описано - на совести и фантазии разработчика!

Ожидаемый результат
----

Обязательно: классы, содержащие данные и реализующие логику расчётов, проверок и т.д. из описания.

Желательно: тесты к написанному коду (в проекте-болванке уже используются Prophecy и PHPUnit).
Комментарии, диаграммы, схемы и т.п. для объяснения принятых решений.


Что не нужно реализовывать
----

Работу с БД и сохранение - можно использовать заглушки (stub-ы и mock-и).
Пользовательский интерфейс (UI)

Что можно использовать
----

Любые инструменты / библиотеки / фреймворки / велосипеды

Комментарий исполнителя
--------

Сменил тематику на наемный труд, чтобы не пересекаться с современной повесткой