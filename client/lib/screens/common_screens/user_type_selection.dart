import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:halqati/widgets/buttons/elevated_dark.dart';
import 'package:halqati/widgets/buttons/elevated_light.dart';

class UserTypeSelection extends StatelessWidget {
  const UserTypeSelection({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        width: double.infinity,
        height: double.infinity,
        decoration: BoxDecoration(
          image: DecorationImage(
            image: AssetImage("assets/images/muz1.png"),
            fit: BoxFit.fill,
          ),
        ),

        child: Column(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            Column(
              children: [
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 40),
                  child: Image.asset("assets/images/alhamidi_logo.png"),
                ),
                Text(
                  'app_name'.tr(),
                  style: Theme.of(context).textTheme.headlineMedium,
                ),
              ],
            ),
            Column(
              spacing: 20,
              children: [
                ElevatedDark(onPressed: (){
                  Navigator.of(context).pushNamed(
                    '/login_screen',
                    arguments: {
                      'userType': 'student', // The parameter key
                    },
                  );
                }, text: "user_type_selection.student".tr(),),
                ElevatedLight(onPressed: (){
                  Navigator.of(context).pushNamed(
                      '/login_screen',
                      arguments: {
                        'userType': 'teacher', // The parameter key
                      },);
                }, text: "user_type_selection.teacher".tr(),),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
