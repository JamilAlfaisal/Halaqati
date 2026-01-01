import 'package:flutter/material.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:halqati/models/halaqa_class.dart';
import 'package:halqati/provider/classes_provider.dart';
import 'package:halqati/widgets/appbar/appbar_with_logo.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/models/teacher.dart';
import 'package:halqati/provider/teacher_provider.dart';
import 'package:halqati/widgets/profile/teacher_profile.dart';
import 'package:intl/intl.dart';

class ProfileScreen extends ConsumerStatefulWidget {
  const ProfileScreen({super.key});

  @override
  ConsumerState<ProfileScreen> createState() => _ProfileScreenState();
}

String getDOB(String ?date){

  if(date == null){
    return "teacher_profile.no_DOB".tr();
  }else{
    return date.split('T')[0];
  }
}

class _ProfileScreenState extends ConsumerState<ProfileScreen> {
  late Teacher ?teacher = ref.watch(teacherProvider);
  late final halaqatDetails = ref.watch(teacherStatsProvider);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppbarWithLogo(text: "profile_screen.profile".tr()),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 30,vertical: 10),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              TeacherProfile(teacher: teacher),
              SizedBox(height: 20,),
              Text(
                "teacher_profile.user_details".tr(),
                style: Theme.of(context).textTheme.bodyLarge,
              ),
              SizedBox(height: 5,),
              Text(
                "${"teacher_profile.email".tr()}: ${teacher?.email??"teacher_profile.no_email".tr()}",
                style: Theme.of(context).textTheme.bodyMedium,
              ),
              SizedBox(height: 5,),
              Text(
                "${"teacher_profile.phone_number".tr()}: ${teacher?.phone??"teacher_profile.no_phone".tr()}",
                style: Theme.of(context).textTheme.bodyMedium,
              ),
              SizedBox(height: 5,),
              Text(
                "${"teacher_profile.DOB".tr()}: ${getDOB(teacher?.dateOfBirth)}",
                style: Theme.of(context).textTheme.bodyMedium,
              ),
              SizedBox(height: 5,),
              Text(
                "${"teacher_profile.password".tr()}: ••••••••",
                style: Theme.of(context).textTheme.bodyMedium,
              ),
              SizedBox(height: 5,),
              GestureDetector(
                onTap: (){
                  final snackBar = SnackBar(
                    content: Text('not_available'.tr()),
                  );

                  // Find the ScaffoldMessenger in the widget tree
                  // and use it to show a SnackBar.
                  ScaffoldMessenger.of(context).showSnackBar(snackBar);
                },
                child: Text(
                  "teacher_profile.change_password".tr(),
                  style: TextStyle(
                    color: Theme.of(context).textTheme.labelSmall!.color,
                    decoration: TextDecoration.underline,
                    decorationColor: Theme.of(context).textTheme.labelSmall!.color,
                    fontSize: Theme.of(context).textTheme.labelSmall!.fontSize
                  ),
                ),
              ),
              SizedBox(height: 20,),
              Text(
                "teacher_profile.halaqat_details".tr(),
                style: Theme.of(context).textTheme.bodyLarge,
              ),
              SizedBox(height: 5,),
              Text(
                "${"teacher_profile.halaqat_number".tr()}: ${halaqatDetails['classCount'].toString()}",
                style: Theme.of(context).textTheme.bodyMedium,
              ),
              SizedBox(height: 5,),
              Text(
                "${"teacher_profile.student_number".tr()}: ${halaqatDetails['studentCount'].toString()}",
                style: Theme.of(context).textTheme.bodyMedium,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
