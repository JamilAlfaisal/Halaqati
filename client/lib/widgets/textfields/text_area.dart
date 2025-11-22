import 'package:flutter/material.dart';

class TextArea extends StatelessWidget {
  final TextEditingController textController;
  final String title;
  const TextArea({
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
            maxLines: null,
            keyboardType: TextInputType.multiline,
            minLines: 5,
            decoration: InputDecoration(

            ),
          ),
        ],
      ),
    );
  }
}
