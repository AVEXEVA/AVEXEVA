Command =@ lambda Packages, Input = eval( Packages['Command']['Prompt'] ) : eval( Packages['Command'].get(eval(Packages['Command']['Symbols']).get( ord(Input[0:1]) )))
