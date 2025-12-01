import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:halqati/widgets/appbar/appbar_with_logo.dart';
import 'package:halqati/widgets/lists/halaqat_list.dart';
import 'package:halqati/widgets/buttons/floating_button_icon.dart';
import 'package:halqati/widgets/textfields/text_field_normal.dart';
import 'package:halqati/widgets/buttons/elevated_dark.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {

  @override
  Widget build(BuildContext context) {
    List<Map<String, dynamic>> dataList = [
      {
        'title': 'Introduction to Flutter',
        'studentNumber': 45,
      },
      {
        'title': 'Advanced Algorithms',
        'studentNumber': 18,
      },
      {
        'title': 'Data Science Fundamentals',
        'studentNumber': 62,
      },
    ];
    return Scaffold(
      appBar: AppbarWithLogo(text: "home_screen.halaqati".tr()),
      floatingActionButton: FloatingButtonIcon(
        onPressed: (){
          Navigator.of(context).pushNamed("/add_halaqah_screen");
      }, text:'home_screen.create'.tr()),
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 30,vertical: 20),
        child: ListView.builder(
          itemCount: dataList.length,
          itemBuilder: (context, index)=>HalaqatList(
            onTap: (){},title: dataList[index]['title'], studentNumber: dataList[index]['studentNumber'],),
        ),
      ),
    );
  }
}
