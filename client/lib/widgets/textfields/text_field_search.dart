import 'package:flutter/material.dart';

class TextFieldSearch extends StatelessWidget {
  final TextEditingController textController;
  final String hintText;

  const TextFieldSearch({
    super.key, required this.hintText,
    required this.textController,
  });

  @override
  Widget build(BuildContext context) {
    return TextFormField(
      controller: textController,
      decoration: InputDecoration(
          hintText: hintText,
          prefixIcon: Icon(Icons.search),
        )
      ,
    );
  }
}
