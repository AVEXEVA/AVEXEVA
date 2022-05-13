#Environment#
##Imports
from Log import Log

##Class
class Environment(object):
    #Imports
    @staticmethod
    def __imports__(self):
        self.Log = Log
        return
    #Initilization
    @staticmethod
    def __init__(self):
        self.__imports__(self)
        self.Space(self)
        return
    @staticmethod
    def Space(self):
        return
        
