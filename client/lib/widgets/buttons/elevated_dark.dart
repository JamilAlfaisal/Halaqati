import 'package:flutter/material.dart';

class ElevatedDark extends StatelessWidget {
  final Function onPressed;
  final String text;
  const ElevatedDark({super.key, required this.onPressed, required this.text});

  @override
  Widget build(BuildContext context) {
    return Align(
      alignment: AlignmentGeometry.center,
      child: ElevatedButton(
        onPressed: ()=>onPressed(),
        style: ButtonStyle(
          shape: WidgetStateProperty.all<RoundedRectangleBorder>(
            RoundedRectangleBorder(
              borderRadius: BorderRadiusGeometry.all(Radius.circular(12)),
            ),
          ),
        ),
          child: Text(text),
      ),
    );
  }
}
