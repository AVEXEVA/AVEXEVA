   #SubModule
   @staticmethod
   def checkSubModule( module ):
      return True if module[0:2] == '__' else False
      
   #Definition
   @staticmethod
   def checkDefinition( module ):
      return True if module[len(module) - 3:] != '.py' else False
      