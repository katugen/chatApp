<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Chat app</title>

    <!-- Styles&Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <h1>Chat app</h1>

    {{-- エンターキーによるボタン押下を行うために、
         <button type="button">ではなく、<form>と<button type="submit">を使用。
         ボタン押下(=submit)時にページリロードが行われないように、
         onsubmitの設定の最後に"return false;"を追加。
         (return false;の結果として、submitが中断され、ページリロードは行われない。） --}}
    <form method="post" action="" onsubmit="onsubmit_Form(); return false;">
        ニックネーム : <input type="text" id="input_nickname" autocomplete="off" />
        <br />
        メッセージ : <input type="text" id="input_message" autocomplete="off" />
        <button type="submit">送信</button>
    </form>

    <ul id="list_message">
    </ul>

    <script>
        const elementInputNickname = document.getElementById("input_nickname");
        const elementInputMessage = document.getElementById("input_message");

        {{-- formのsubmit処理 --}}

        function onsubmit_Form() {
            {{-- 送信用テキストHTML要素からメッセージ文字列の取得 --}}
            let strNickname = elementInputNickname.value;
            let strMessage = elementInputMessage.value;
            if (!strMessage) {
                return;
            }

            if (!strNickname) {
                strNickname = '(匿名)';
            }
            params = {
                'nickname': strNickname,
                'message': strMessage
            };

            {{-- POSTリクエスト送信処理とレスポンス取得処理 --}}
            axios
                .post('', params)
                .then(response => {
                    console.log(response);
                })
                .catch(error => {
                    console.log(error.response)
                });

            {{-- テキストHTML要素の中身のクリア --}}
            elementInputMessage.value = "";
        }

        {{-- ページ読み込み後の処理 --}}
        window.addEventListener("DOMContentLoaded", () => {
            const elementListMessage = document.getElementById("list_message");

            {{-- Listen開始と、イベント発生時の処理の定義 --}}
            window.Echo.channel('chat').listen('MessageSent', (e) => {
                console.log(e);
                {{-- メッセージの整形 --}}
                let strNickname = e.message.nickname;
                let strMessage = e.message.body;

                {{-- 拡散されたメッセージをメッセージリストに追加 --}}
                let elementLi = document.createElement("li");
                let elementNickname = document.createElement("strong");
                let elementMessage = document.createElement("div");
                elementNickname.textContent = strNickname;
                elementMessage.textContent = strMessage;
                elementLi.append(elementNickname);
                elementLi.append(elementMessage);
                elementListMessage.prepend(elementLi); // リストの一番上に追加
                //elementListMessage.append( elementLi ); // リストの一番下に追加
            });
        });
    </script>
</body>

</html>
