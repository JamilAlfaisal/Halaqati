import 'package:flutter/material.dart';

class FullWidthButton extends StatelessWidget {
  final Function onPressed;
  final String text;
  const FullWidthButton({super.key, required this.onPressed, required this.text});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 30),
      child: ElevatedButton(
        style: ButtonStyle(

        ),
        onPressed: ()=>onPressed(),
        child: Text(text),
      ),
    );
  }
}
