---
layout: default
---
<pre>
{{page.date}}
{{page.sermon.scripture}}
{{page.sermon.title}}

{{page.date|format_time}}
{{page.sermon.scripture | scripture_url}}
{{page.sermon.title | title_url}}
</pre>
