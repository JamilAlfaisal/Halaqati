import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/models/halaqa_class.dart';
import 'package:halqati/models/student.dart';
import 'package:halqati/provider/get_classes_provider.dart';
import 'package:halqati/widgets/appbar/appbar_with_button.dart';
import 'package:halqati/widgets/textfields/text_field_search.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:halqati/widgets/lists/student_list.dart';
import 'package:halqati/widgets/buttons/floating_button_icon.dart';

class HalaqahScreen extends StatefulWidget {
  final HalaqaClass halaqah;
  const HalaqahScreen({super.key, required this.halaqah});

  @override
  State<HalaqahScreen> createState() => _HalaqahScreenState();
}

class _HalaqahScreenState extends State<HalaqahScreen> {
  final TextEditingController search = TextEditingController();
  @override
  Widget build(BuildContext context) {
    final List<Student> students = widget.halaqah.students ?? [];

    return Scaffold(
      floatingActionButton: FloatingButtonIcon(text: "halaqah_screen.students.add_student".tr(), onPressed: (){}),
      appBar: AppbarWithButton(addBackButton: true, text: "Text",),
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 30),
        child: Column(
          spacing: 10,
          children: [
            TextFieldSearch(textController: search, hintText: "halaqah_screen.students.search".tr(),),
            students.isNotEmpty?
            Expanded(
              child: ListView.builder(
                itemCount: students.length,
                itemBuilder: (context, index){
                  // final List<Student>? students = widget.halaqah.students;
                  return StudentList(
                    name: students[index].name ?? "Name not found",
                    totalPagesMemorized: students[index].memorizedPages?.length ?? 0,
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

