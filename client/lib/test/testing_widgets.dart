import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:halqati/widgets/buttons/elevated_dark.dart';
import 'package:halqati/widgets/buttons/elevated_light.dart';
import 'package:halqati/widgets/buttons/text_button_light.dart';
import 'package:halqati/widgets/buttons/delete_button.dart';
import 'package:halqati/widgets/buttons/verse_number.dart';
import 'package:halqati/widgets/buttons/filter_button.dart';
import 'package:halqati/widgets/buttons/floating_button_icon.dart';
import 'package:halqati/widgets/buttons/full_width_button.dart';
import 'package:halqati/widgets/appbar/appbar_with_logo.dart';
import 'package:halqati/widgets/appbar/appbar_with_button.dart';
import 'package:halqati/widgets/textfields/text_field_normal.dart';
import 'package:halqati/widgets/textfields/text_field_password.dart';
import 'package:halqati/widgets/textfields/text_field_date.dart';
import 'package:halqati/widgets/textfields/text_area.dart';
import 'package:halqati/widgets/textfields/text_field_search.dart';
import 'package:halqati/widgets/lists/halaqat_list.dart';
import 'package:halqati/widgets/lists/student_list.dart';
import 'package:halqati/widgets/lists/assignment_list.dart';

class TestingWidgets extends StatelessWidget {
  const TestingWidgets({super.key});

  @override
  Widget build(BuildContext context) {
    final TextEditingController _textController = TextEditingController();
    return Scaffold(
      // appBar:  AppbarWithLogo(
      //   text: 'Test screen',
      // ),
      appBar: AppbarWithButton(
        text: "Test",
        addBackButton: false,
      ),
      floatingActionButton: FloatingButtonIcon(onPressed: (){print("floating button");}, text:'add student'),
      floatingActionButtonLocation: FloatingActionButtonLocation.endFloat,
      body: ListView(
        children: [
          // ElevatedDark(onPressed: (){print("Teacher");}, text: "Create Teacher Account",),
          // ElevatedLight(onPressed: (){print("Student");}, text: "Create Student Account",),
          // TextButtonLight(onPressed: (){print("TextButton");}, text: "Press Text Button",),
          // DeleteButton(onPressed: (){print("TextButton");}),
          // VerseNumber(onPressed: (){print('verse button');}, num: 100,selected: true,),
          // VerseNumber(onPressed: (){print('verse button');}, num: 1,selected: false,),
          // FilterButton(onPressed: (){print('filter button');}, text: 'all'),
          // FullWidthButton(onPressed: (){print('Full Width Button');}, text: 'Sign Up'),
          // TextFieldNormal(hintText: "Your Email",textController: _textController,title: "Enter your email",),
          // TextFieldPassword(hintText: "Your Password",textController: _textController,title: "Enter your password",),
          // TextFieldDate(hintText: "Select Date",textController: _textController,title: "Event Date",),
          // TextArea(textController: _textController,title: "Event Description",),
          TextFieldSearch(hintText: "Search using student ID #",textController: _textController),
          HalaqatList(title: "Halaqet Alhamidi", studentNumber: 12,onTap: (){print("Nav to halaqat");}),
          StudentList(name: "Jamil Alfaisal", totalPagesMemorized: 200, onTap: (){print("Nav to student");},),
          AssignmentList(title: "Surah AL-Baqqara",dueDate: "2024-01-15", onPressed: (){print("Delete");},),
        ],
      ),
    );
  }
}
