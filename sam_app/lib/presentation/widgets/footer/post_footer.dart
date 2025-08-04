import 'package:flutter/material.dart';

class PostFooter extends StatelessWidget {
  final int comments;
  final int likes;

  const PostFooter({
    super.key,
    required this.comments,
    required this.likes,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Row(
          children: [
            const Icon(Icons.mode_comment_outlined, color: Colors.white54, size: 20),
            const SizedBox(width: 4),
            Text('$comments', style: const TextStyle(color: Colors.white54, fontSize: 12)),
          ],
        ),
        Row(
          children: const [
            Icon(Icons.favorite_border, color: Colors.white54, size: 20),
          ],
        ),
      ],
    );
  }
}