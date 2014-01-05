---
layout: default
---

{{page.date}}

{{ page.ace.title }}
====================

{{page.ace.series}}
-------------------

{{page.title}}
By {{page.teacher}}

Recording:
[ACE Recording](recording.path)

{% if page.ace.comment %}
Comment: {{page.ace.comment}}
{% endif %}


{{ page.sermon.title }}
====================

{{page.sermon.series}}
-------------------

Recording:
[Sermon Recording](recording.path)

Label Info:
{{page.date}}, {{page.sermon.preacher}}, {{page.sermon.title}}
Scripture: {{page.sermon.tracks[1].time}}
Total: {{page.sermon.total}}

{% if page.sermon.comment %}
Comment: {{page.sermon.comment}}
{% endif %}


