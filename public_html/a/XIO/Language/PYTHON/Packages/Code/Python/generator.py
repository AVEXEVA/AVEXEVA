class generator:
    def __init__(self):
        self.home()
    def home(self):
        self.user_prompt_process(self.user_prompt("Options: [Generate Table], [Alter  Table]\n"))
    def user_prompt(self, message):
        return input("" + message)
    def user_prompt_process(self, response):
        if response == 'Home':
            self.home()
        elif response == 'Generate Table':
            self.generate_table()
        elif response == 'Alter Table':
            self.alter_table()
        else:
            self.home()
    def generate_table(self):
        Table_Name = self.user_prompt('Please name your table: ')
        print("---------------" + Table_Name + "---------------")
        Column_Name = ''
        Pass = 0
        Columns = [];
        while(Column_Name != 'Done'):
            Pass = Pass + 1
            Column_Name = self.user_prompt(str(Pass) + '). Please name your column: ')
            if Column_Name == 'Done':continue
            Datatype = self.user_prompt(str(Pass) + '). ' + Column_Name + '. Please enter a datatype: ')
            Columns.append(Column_Name + ' ' + Datatype)
        print("---------------" + Table_Name + "---------------")
        Pass = 0;
        for Column in Columns:
            Pass = Pass + 1
            print(str(Pass) + '). ' + Column
        
    def alter_table(self):
        return
generator()
