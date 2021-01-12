class Time(object):
    def __init__(self):
        return
    @staticmethod
    def ISO(Year, Month, Day):
        Year    = Year      #if Year[2].isdigit() else '00' + Year
        Month   = Month     if int(Month)   <= 9    else '0' + str(Month)
        Day     = Day       if int(Day)     <= 9    else '0' + str(Day)
        return Year + '-' + Month + '-' + Day
    @staticmethod
    def ChristA(self, Year, Month, Day):
        Dates = []
        while Year >= 0:
            Dates.append(self.ISO(Year, Month, Day))
            Year = Year - 1
        return Dates
