import 'package:flutter/material.dart';

class ColorUtils {
  static Color colorFromString(String input) {
    final hash = input.codeUnits.fold(0, (prev, elem) => prev + elem);
    final hue = hash % 360;
    const saturation = 0.65;
    const lightness = 0.30;

    return HSLColor.fromAHSL(
      1.0,
      hue.toDouble(),
      saturation,
      lightness,
    ).toColor();
  }
}
