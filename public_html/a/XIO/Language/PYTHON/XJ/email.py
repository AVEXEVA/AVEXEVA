#Imports
from smtplib

from email.message import EmailMessage

#Email
class Email
    #Import
    @staticmethod
    def __import__(self):
        self.EmailMessage = EmailMessage()
        return True
    
    #Initializer
    def __init__(self, Who, What, Where, Why, When, How, Knowledge):
        if self.__import__(self):
            print('Imported')
        if self, self, Who, What, Where, Why, When, How, Knowledge():
            print('Set Variables')
        

    #Set Variables
    def __set__(self, Who, What, Where, Why, When, How, Knowledge):
        self.Who = Who
        self.What = What
        self.Where = Where
        self.When = WHen
        self.Why = Why
        self.How = How
        self.Knowledge = Knowledge

    def Message():
        message = EmailMessage()
        message.set_content(fp.read())
        message['Subject'] = 'AVEXEVA'
        message['To'] = self.Who
        message['From'] = 'Peter Donald Speranza'
        s = smtplib.SMTP('localhost') 
        s.send_message
        s.quit()
        quit()
