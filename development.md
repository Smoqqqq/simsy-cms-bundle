# While in development

Dump JS traductions (I here assume that you have installed the bundle in a local symfony project)
```bash
php bin/console bazinga:js-translation:dump vendor/smoq/simsy-cms-bundle/src/Resources/public
php bin/console assets:install --symlink
```

icons
```html
    <div style="
        margin: 50vh auto;
        padding: 25px;
        width: fit-content;
        border: 5px solid black;
        border-radius: 20px;
        display: flex;
        flex-direction: row;
        gap: 25px;
        align-items: center;
        justify-content: center;
    ">
        <div style="
            width: 250px;
            border: 5px solid black;
            border-radius: 20px;
            font-size: 40px;
            padding: 15px;
            display: grid;
            place-items: center;
        ">
            <i class="bi bi-play-fill" style="font-size: 70px; line-height: 60px"></i>
        </div>
    </div>
```