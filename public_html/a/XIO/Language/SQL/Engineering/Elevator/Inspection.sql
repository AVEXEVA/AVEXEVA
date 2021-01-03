USE [Portal]
GO

/****** Object:  Table [dbo].[Inspection]    Script Date: 12/20/2018 9:30:55 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Inspection](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[Company] [varchar](255) NULL,
	[Date] [varchar](255) NULL,
	[Mechanic] [varchar](255) NULL,
	[License_Number] [varchar](255) NULL,
	[Start_Time] [varchar](255) NULL,
	[End_Time] [varchar](255) NULL,
	[Building_Address] [varchar](255) NULL,
	[Inspected_By] [varchar](255) NULL,
	[Elevator_Number] [varchar](255) NULL,
	[Device_Number] [varchar](255) NULL,
	[Category1] [varchar](255) NULL,
	[Category5] [varchar](255) NULL,
	[Signed] [varchar](255) NULL,
	[Violation_Elevator_Part1] [varchar](255) NULL,
	[Violation_Elevator_Part2] [varchar](255) NULL,
	[Violation_Elevator_Part3] [varchar](255) NULL,
	[Violation_Elevator_Part4] [varchar](255) NULL,
	[Violation_Elevator_Part5] [varchar](255) NULL,
	[Violation_Elevator_Part6] [varchar](255) NULL,
	[Violation_Elevator_Part7] [varchar](255) NULL,
	[Violation_Elevator_Part8] [varchar](255) NULL,
	[Violation_Elevator_Part9] [varchar](255) NULL,
	[Violation_Elevator_Part10] [varchar](255) NULL,
	[Violation_Condition1] [varchar](255) NULL,
	[Violation_Condition2] [varchar](255) NULL,
	[Violation_Condition3] [varchar](255) NULL,
	[Violation_Condition4] [varchar](255) NULL,
	[Violation_Condition5] [varchar](255) NULL,
	[Violation_Condition6] [varchar](255) NULL,
	[Violation_Condition7] [varchar](255) NULL,
	[Violation_Condition8] [varchar](255) NULL,
	[Violation_Condition9] [varchar](255) NULL,
	[Violation_Condition10] [varchar](255) NULL,
	[Violation_Suggested_Remedy1] [varchar](255) NULL,
	[Violation_Suggested_Remedy2] [varchar](255) NULL,
	[Violation_Suggested_Remedy3] [varchar](255) NULL,
	[Violation_Suggested_Remedy4] [varchar](255) NULL,
	[Violation_Suggested_Remedy5] [varchar](255) NULL,
	[Violation_Suggested_Remedy6] [varchar](255) NULL,
	[Violation_Suggested_Remedy7] [varchar](255) NULL,
	[Violation_Suggested_Remedy8] [varchar](255) NULL,
	[Violation_Suggested_Remedy9] [varchar](255) NULL,
	[Violation_Suggested_Remedy10] [varchar](255) NULL,
	[Pass] [varchar](255) NULL,
	[Fail] [varchar](255) NULL,
	[Retest] [varchar](255) NULL,
	[Pictures] [varchar](255) NULL,
	[Inspection_d] [varchar](255) NULL,
	[Code_Data_Plate] [varchar](255) NULL,
	[Code_Data_Plate_Type] [bit] NULL,
	[Controller] [varchar](255) NULL,
	[Machine] [varchar](255) NULL,
	[Speed] [varchar](255) NULL,
	[Last_Category1_Tag] [varchar](255) NULL,
	[Last_Category5_Tag] [varchar](255) NULL,
	[Comments] [varchar](255) NULL,
	[Runby] [varchar](255) NULL,
	[Normal_Up] [varchar](255) NULL,
	[Normal_Down] [varchar](255) NULL,
	[Final_Up] [varchar](255) NULL,
	[Final_Down] [varchar](255) NULL,
	[Car_Oil_Buffers] [varchar](255) NULL,
	[Cwt_Oil_Buffers] [varchar](255) NULL,
	[Governor_Switch] [varchar](255) NULL,
	[Plank_Switch] [varchar](255) NULL,
	[Slack_Rope_Switch] [varchar](255) NULL,
	[Governor_Cal] [varchar](255) NULL,
	[Maintenance_Elevator_Part1] [varchar](255) NULL,
	[Maintenance_Elevator_Part2] [varchar](255) NULL,
	[Maintenance_Elevator_Part3] [varchar](255) NULL,
	[Maintenance_Elevator_Part4] [varchar](255) NULL,
	[Maintenance_Elevator_Part5] [varchar](255) NULL,
	[Maintenance_Elevator_Part6] [varchar](255) NULL,
	[Maintenance_Elevator_Part7] [varchar](255) NULL,
	[Maintenance_Elevator_Part8] [varchar](255) NULL,
	[Maintenance_Elevator_Part9] [varchar](255) NULL,
	[Maintenance_Elevator_Part10] [varchar](255) NULL,
	[Maintenance_Condition1] [varchar](255) NULL,
	[Maintenance_Condition2] [varchar](255) NULL,
	[Maintenance_Condition3] [varchar](255) NULL,
	[Maintenance_Condition4] [varchar](255) NULL,
	[Maintenance_Condition5] [varchar](255) NULL,
	[Maintenance_Condition6] [varchar](255) NULL,
	[Maintenance_Condition7] [varchar](255) NULL,
	[Maintenance_Condition8] [varchar](255) NULL,
	[Maintenance_Condition9] [varchar](255) NULL,
	[Maintenance_Condition10] [varchar](255) NULL,
	[Maintenance_Suggested_Remedy1] [varchar](255) NULL,
	[Maintenance_Suggested_Remedy2] [varchar](255) NULL,
	[Maintenance_Suggested_Remedy3] [varchar](255) NULL,
	[Maintenance_Suggested_Remedy4] [varchar](255) NULL,
	[Maintenance_Suggested_Remedy5] [varchar](255) NULL,
	[Maintenance_Suggested_Remedy6] [varchar](255) NULL,
	[Maintenance_Suggested_Remedy7] [varchar](255) NULL,
	[Maintenance_Suggested_Remedy8] [varchar](255) NULL,
	[Maintenance_Suggested_Remedy9] [varchar](255) NULL,
	[Maintenance_Suggested_Remedy10] [varchar](255) NULL,
	[Run_Pressure] [varchar](255) NULL,
	[Work_Pressure] [varchar](255) NULL,
	[Relief_Pressure] [varchar](255) NULL,
	[Hydraulic_Pressure] [varchar](255) NULL,
	[Notes] [varchar](255) NULL,
	[Car_Safety_Set] [varchar](255) NULL,
	[Car_Slip_Traction] [varchar](255) NULL,
	[Car_Stall] [varchar](255) NULL,
	[Cwt_Safety_Test] [varchar](255) NULL,
	[Cwt_Slip_Traction] [varchar](255) NULL,
	[Cwt_Stall] [varchar](255) NULL,
	[Date_Form_Received] [varchar](255) NULL,
	[Directors_Initals] [varchar](255) NULL,
	[Directors_Changes] [varchar](255) NULL,
	[Date_ELV3_Created] [varchar](255) NULL,
	[Invoice_Number] [varchar](255) NULL,
	[Date_Invoice_Created] [varchar](255) NULL,
	[Retest_Notes] [varchar](255) NULL,
	[Retest_Billed] [varchar](255) NULL,
	[Retest_Dates] [varchar](255) NULL,
PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO


