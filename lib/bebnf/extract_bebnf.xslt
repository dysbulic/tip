<stylesheet version='1.0'
            xmlns='http://www.w3.org/1999/XSL/Transform'
            xmlns:html='http://www.w3.org/1999/xhtml'>
  <output method='text' standalone='no' /> 
  <template match='/'>
    <apply-templates select='//html:div[@class = \"ebnf spec\"]/html:code' />
  </template>
  <template match='html:code'>
    <apply-templates /><text>&#x0A;</text>
  </template>
</stylesheet>
