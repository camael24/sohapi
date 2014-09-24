// λ cat data\ns2.php | bin\hoa compiler:pp src\Parser\Php\Grammar.pp 0 --visitor dump
%skip   space           \s
%skip   tag_            <\?(php)?
%skip   _tag            \?>

// COMMENT
%skip slash //[^\v$]*
%skip block_comment /\*(.|\n)*?\*/

// Classe & Namespace
%token  namespace       namespace
%token  use             use
%token  class           class
%token  interface       interface
%token  abstract        abstract
%token  implements      implements
%token  extends         extends
%token  function        function
%token  const           const
%token  brace_          {
%token  _brace          }
%token  comma           ,+
%token  colon           :+
%token  semicolon       ;+
%token  staccess        self::(\w+)

// Visibility
%token  public          public
%token  protected       protected
%token  private         private
%token  static          (static|self)

// Variable & Default value
%token  array           array
%token  null            null
%token  true            true
%token  false           false
%token  variable        \$(\w+)
%token  associate       =>
%token  equal           =

//Methods
%token  parenthesis_    \(
%token  _parenthesis    \)

//Values

%token  int             \d+
%token  float           \d+\.\d+
%token  quoted          ("|')(.*?)(?<!\\)\1
%token  string          [^{(:,;\=\$\s\)]+

#file:
    ( namespace() | use() | class() )+

#namespace:
    ::namespace:: classname()? ( ( ::brace_:: use()? class()* ::_brace:: ) | ::semicolon:: use()? )

classname:
    <string>

#use:
    ::use:: classname() (::comma:: classname())* ::semicolon::

#class:
    ::class:: classname() extends()? implements()? ::brace_:: classcontent()? ::_brace::

#extends:
    ::extends:: classname()

#implements:
    ::implements:: classname() (::comma:: classname())*

classcontent:
    ( method() | const() | property() )*

#method:
    visibility() ::function:: <string> ::parenthesis_:: arguments()? ::_parenthesis:: ::brace_:: ::_brace::

#const:
    ::const:: <string> ::equal:: default() ::semicolon::

#property:
    visibility() <variable> (::equal:: default() )? ::semicolon::

visibility:
    (<private> | <protected> | <public>)? <static>?

#arguments:
    variable() ( ::comma::  variable())*

default:
    <true> | <false> | <null> | array() | <quoted> | <staccess> | <int> | <float> | <string> ::colon:: <string>

#array:
    ::array:: ::parenthesis_:: (array() ::comma::? | key())* ::_parenthesis::

#key:
    default() (::associate:: default() #keyval)? ::comma::?

#variable:
    classname()? <variable> (::equal:: default())?

