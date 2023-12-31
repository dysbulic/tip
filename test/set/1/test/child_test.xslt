<stylesheet xmlns="http://www.w3.org/1999/XSL/Transform" xmlns:out="test:out" version="1.0">
  <template match="/">
    <out:top><apply-templates select="/*/child::*[1]"/></out:top>
    <out:bottom><apply-templates select="/*/child::*[1]/following-sibling::*"/></out:bottom>
  </template>
  <template match="*">
    <out:node>
      <value-of select="name()" />
      <text> </text>
      <value-of select="name(@*)" />=<value-of select="@*" />
    </out:node>
  </template>
</stylesheet>
