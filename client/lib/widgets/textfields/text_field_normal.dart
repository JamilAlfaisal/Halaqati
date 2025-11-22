import 'package:flutter/material.dart';

class TextFieldNormal extends StatelessWidget {
  final TextEditingController textController;
  final String title;
  final String hintText;

  const TextFieldNormal({
    super.key, required this.hintText,
    required this.textController,
    required this.title
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 30),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.center,
        spacing: 5,
        children: [
          Text(
            title,
            style: TextTheme.of(context).bodyLarge,
          ),
          TextFormField(
            controller: textController,
            decoration: InputDecoration(
              hintText: hintText,
            ),
          ),
        ],
      ),
    );
  }
}
