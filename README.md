# **Xml-парсер**

**_В проекте есть возможность загружать файлы, производить проверку файлов на соответствие заданной сигнатуре, отображать все сохраненные файлы с учетом фильтров и сортировки, выводить выбранный файл в отдельном шаблоне._**
**_Помимо этого, в проекте реализована API с возможностью получать файлы, хранящиеся на сервере, в формате JSON._**

## Структура проекта:

* Прописанные маршруты в routes/web.php и routes/api.php;
* Контроллер app/Http/Controllers/FileController.php с методами для взаимодействия с http запросами;
* Сервис-класс app/Services/XmlParserService.php с методом по проверке xml-файла на соответствие заданной сигнатуре, сохранением валидного файла на сервер и заносом информации о нем в БД. Так же в сервисе имеется метод для трансформации XML файла в формат JSON;
* Шаблоны представления в resources/views (по-хорошему нужно разнести стили и js-скрипты по отдельным файлам, но в рамках этого задания думаю это не критично)

