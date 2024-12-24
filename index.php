<?php
//<title>书签生成器</title>
//<meta name="description" content="Chrome 书签生成器">
$generatedHtml = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取用户输入的 URL 列表
    $urls = isset($_POST['urls']) ? trim($_POST['urls']) : '';
    
    // 解析为数组（按行分割）
    $urlList = array_filter(explode("\n", $urls), 'trim');
    
    // 定义书签文件的起始模板
    $generatedHtml = <<<HTML
<!DOCTYPE NETSCAPE-Bookmark-file-1>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<TITLE>书签</TITLE>
<H1>书签</H1>
<DL><p>
HTML;

    // 将每个 URL 添加到书签中
    foreach ($urlList as $url) {
        $url = htmlspecialchars(trim($url)); // 转义 HTML 特殊字符
        $generatedHtml .= "\n    <DT><A HREF=\"$url\">$url</A>";
    }

    // 结束书签 HTML
    $generatedHtml .= "\n</DL><p>";
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>书签生成器</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        textarea { width: 100%; font-size: 14px; margin-bottom: 10px; }
        pre { width: 98%; font-size: 14px; margin-bottom: 10px; }
        textarea { height: 200px; outline: none; border: 1px solid #ccc; }
        pre { 
            background: #f4f4f4; 
            padding: 10px; 
            border: 1px solid #ddd; 
            overflow-x: auto; 
            height: 400px; 
            white-space: pre-wrap; 
            word-wrap: break-word; 
            margin-top: 10px; 
            resize: vertical; 
        }
        button { padding: 10px 20px; font-size: 16px; border: none; cursor: pointer; }
        button:hover { opacity: 0.9; }
        .generate-btn { background-color: #007BFF; color: white; }
        .copy-btn { background-color: #28A745; color: white; margin-left: 10px; }
        .copy-btn.copied { background-color: #2ecc71; }
    </style>
    <script>
        function copyToClipboard() {
            const content = document.getElementById('output');
            const copyButton = document.querySelector('.copy-btn');

            // 复制到剪切板
            const tempTextarea = document.createElement('textarea');
            tempTextarea.value = content.textContent;
            document.body.appendChild(tempTextarea);
            tempTextarea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextarea);

            // 按钮文字变化
            copyButton.textContent = '已复制';
            copyButton.classList.add('copied');

            // 重置页面
            setTimeout(() => {
                document.getElementById('urls').value = '';
                document.getElementById('output').textContent = '';
                copyButton.textContent = '复制到剪贴板';
                copyButton.classList.remove('copied');
            }, 3000); // 3 秒后重置页面
        }
    </script>
</head>
<body>
    <h1>Chrome 书签生成器</h1>
    <form method="POST" action="">
        <label for="urls">请输入多个网址（每行一个）：</label><br>
        <textarea name="urls" id="urls" placeholder="https://example.com&#10;https://example2.com"></textarea><br>
        <button type="submit" class="generate-btn">生成书签</button>
        <button type="button" class="copy-btn" onclick="copyToClipboard()">复制到剪贴板</button>
    </form>

    <p>生成的书签内容：</p>
    <pre id="output"><?= htmlspecialchars($generatedHtml) ?></pre>
</body>
</html>