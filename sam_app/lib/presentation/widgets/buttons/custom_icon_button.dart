import 'package:flutter/material.dart';
import 'package:sam_app/presentation/widgets/snack/top_snack_bar.dart';

class CustomIconButton extends StatefulWidget {
  final String label;
  final Color color;
  final IconData icon;
  final Future<void> Function() onPressed;

  const CustomIconButton({
    super.key,
    required this.label,
    required this.color,
    required this.icon,
    required this.onPressed,
  });

  @override
  State<CustomIconButton> createState() => _CustomIconButtonIconState();
}

class _CustomIconButtonIconState extends State<CustomIconButton> {

  @override
  Widget build(BuildContext context) {
    return ElevatedButton(
      onPressed: () async {
        try {
          await widget.onPressed();
        } catch (e) {
          if (context.mounted) {
            TopSnackBar.show(
              context,
              e.toString().replaceFirst('Exception: ', ''),
              color: Colors.red[700],
            );
          }
        }
      },
      style: ElevatedButton.styleFrom(
        backgroundColor: widget.color,
        padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(widget.icon, color: Colors.white),
          const SizedBox(width: 8),
          Text(
            widget.label,
            style: const TextStyle(color: Colors.white, fontSize: 16),
          ),
        ],
      ),
    );
  }
}