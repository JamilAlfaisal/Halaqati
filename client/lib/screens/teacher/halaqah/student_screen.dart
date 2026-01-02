import 'package:flutter/material.dart';
// import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/models/halaqa_class.dart';
import 'package:halqati/models/student.dart';
// import 'package:halqati/provider/classes_provider.dart';
import 'package:halqati/widgets/appbar/appbar_with_button.dart';
import 'package:halqati/widgets/textfields/text_field_search.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:halqati/widgets/lists/student_list.dart';
import 'package:halqati/widgets/buttons/floating_button_icon.dart';

class StudentScreen extends StatefulWidget {
  const StudentScreen({super.key});

  @override
  State<StudentScreen> createState() => _HalaqahScreenState();
}

class _HalaqahScreenState extends State<StudentScreen> {
  final TextEditingController search = TextEditingController();
  
  late List<Student> students =  [];
  late List<Student> searchedStudents = students;

  @override
  Widget build(BuildContext context) {

    return Scaffold(
      floatingActionButton: FloatingButtonIcon(text: "halaqah_screen.students.add_student".tr(), onPressed: (){}),
      appBar: AppbarWithButton(addBackButton: true, text: "Text",),
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 30),
        child: Column(
          spacing: 10,
          children: [
            TextFieldSearch(
              textController: search,
              hintText: "halaqah_screen.students.search".tr(),
              onChange: (value){
                setState(() {
                  if(value.isEmpty){
                    print("WOWWOWOWW");
                    searchedStudents = students;
                  }else{
                    final List<Student> filtered = students.where((student){
                      return student.name != null && student.name!.toLowerCase().trim().contains(
                          search.text.toLowerCase().trim());
                    }).toList();
                    searchedStudents = filtered;
                  }
                });
              },
            ),
            searchedStudents.isNotEmpty?
            Expanded(
              child: ListView.builder(
                itemCount: searchedStudents.length,
                itemBuilder: (context, index){
                  // final List<Student>? seatchedStudents = widget.halaqah.students;
                  return StudentList(
                    name: searchedStudents[index].name ?? "Name not found",
                    totalPagesMemorized: searchedStudents[index].memorizedPages?.length ?? 0,
                    onTap: (){}
                  );
                }
              ),
            ):Expanded(
              child: Center(
                child: Text(
                  "halaqah_screen.students.no_students".tr(),
                  style: Theme.of(context).textTheme.bodyLarge,
                )
              ),
            )
          ],
        ),
      ),
    );
  }
}

