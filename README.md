# udemy Laravel講座

## インストール方法

## インストール後の実施事項

画像のダミーデータは`public/images`フォルダ内に`sample1.jpg`〜`sample6.jpg`として 保存しています。

`php artisan storage:link`で storageフォルダにリンク後、

`storage/app/public/products`フォルダ内に 保存すると表示されます。 (productsフォルダがない場合は作成してください。)

ショップの画像も表示する場合は、`storage/app/public/shops`フォルダを作成し 画像を保存してください。