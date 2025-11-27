import 'package:flutter/material.dart';

class EventsList extends StatelessWidget {
  final String date;
  final String title;
  final String description;
  final void Function()? onTap;
  const EventsList({
    super.key,
    required this.title,
    required this.date,
    required this.description,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            date,
            style: TextTheme.of(context).titleSmall,
          ),
          Text(
            description,
            style: TextTheme.of(context).bodyLarge,
          ),
          Text(
            title,
            style: TextTheme.of(context).titleSmall,
          ),
          Divider(
            color: Theme.of(context).colorScheme.secondaryContainer,
            height: 20, // Specifies the total height of the divider area
            thickness: 2, // Specifies the thickness (height) of the line itself
            indent: 10, // Indentation (empty space) at the start of the divider
            endIndent: 10, // Indentation (empty space) at the end of the divider
          ),
        ],
      ),
    );
  }
}
